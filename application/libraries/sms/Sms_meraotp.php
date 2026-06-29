<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sms_meraotp extends App_sms
{
    private $api_key;
    private $brand_name;
    private $sender_id;
    private $apiUrl = 'https://meraotp.in/api/sendSMS';

    public function __construct()
    {
        parent::__construct();

        $this->api_key    = $this->get_option('meraotp', 'api_key') ?: '5a697992860ef6ec1cd7b166c0';
        $this->brand_name = $this->get_option('meraotp', 'brand_name') ?: 'Nooryak CRM';
        $this->sender_id  = $this->get_option('meraotp', 'sender_id') ?: 'MRAOTP';

        $this->add_gateway('meraotp', [
            'name'    => 'MeraOTP',
            'options' => [
                [
                    'name'  => 'api_key',
                    'label' => 'API Key',
                ],
                [
                    'name'  => 'brand_name',
                    'label' => 'Brand Name',
                ],
                [
                    'name'  => 'sender_id',
                    'label' => 'Sender ID',
                ],
            ],
        ]);
    }

    public function send($number, $message)
    {
        // Sanitize phone number to contain only numeric digits
        $number = preg_replace('/[^\d]/', '', $number);

        // Extract 6-digit OTP from message
        $otp = '';
        if (preg_match('/\b\d{6}\b/', $message, $matches)) {
            $otp = $matches[0];
        }

        if (empty($otp)) {
            // Default/fallback OTP if not found in the message
            $otp = '000000';
        }

        try {
            $payload = [
                'apiKey'      => $this->api_key,
                'mobileNo'    => $number,
                'messageType' => 'AUTH_OTP',
                'brandName'   => $this->brand_name,
                'otp'         => $otp,
                'senderId'    => $this->sender_id,
            ];

            $response = $this->client->request('POST', $this->apiUrl, [
                'json'    => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ]
            ]);

            $result = json_decode($response->getBody());

            if (isset($result->success) && $result->success === true) {
                $this->logSuccess($number, $message);
                return true;
            }

            $errorMsg = isset($result->message) ? $result->message : 'Unknown error';
            $this->set_error($errorMsg);
        } catch (Exception $e) {
            $this->set_error($e->getMessage());
        }

        return false;
    }
}
