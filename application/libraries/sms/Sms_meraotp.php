<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sms_meraotp extends App_sms
{
    private $api_key;
    private $apiUrl = 'https://2fa.tehub.in/api/whatsapp.php';

    public function __construct()
    {
        parent::__construct();

        $db_key = $this->get_option('meraotp', 'api_key');
        if (empty($db_key) || strpos($db_key, 'teh_api_') !== 0) {
            $this->api_key = 'teh_api_47dbc4f2285eeadfcdc8b60edc25f4ae';
        } else {
            $this->api_key = $db_key;
        }

        $this->add_gateway('meraotp', [
            'name'    => 'WhatsApp API (Tehub)',
            'options' => [
                [
                    'name'  => 'api_key',
                    'label' => 'API Key (Bearer Token)',
                ],
            ],
        ]);
    }

    public function send($number, $message)
    {
        // Sanitize phone number to contain only numeric digits
        $number = preg_replace('/[^\d]/', '', $number);

        // Prepend country code 91 if it's 10 digits
        if (strlen($number) === 10) {
            $number = '91' . $number;
        }

        try {
            $payload = [
                'to'      => $number,
                'message' => $message,
                'type'    => 'otp',
            ];

            $response = $this->client->request('POST', $this->apiUrl, [
                'json'    => $payload,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->api_key,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ]
            ]);

            $result = json_decode($response->getBody());

            if (isset($result->success) && $result->success === true) {
                $this->logSuccess($number, $message);
                return true;
            }

            $errorMsg = isset($result->error) ? $result->error : (isset($result->message) ? $result->message : 'Unknown error');
            $this->set_error($errorMsg);
        } catch (Exception $e) {
            $this->set_error($e->getMessage());
        }

        return false;
    }
}

