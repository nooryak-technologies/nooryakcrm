<?php

defined('BASEPATH') or exit('No direct script access allowed');

class New_updates_wp extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if ($this->input->post()) {
            $message = $this->input->post('message', true);
            $selected_staff = $this->input->post('staff');
            $selected_leads = $this->input->post('leads');

            $attachment_base64 = null;
            $attachment_filename = null;

            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
                $file_size = $_FILES['attachment']['size'];
                if ($file_size > 30 * 1024 * 1024) {
                    set_alert('danger', 'Attachment exceeds the 30MB limit.');
                    redirect(admin_url('new_updates_wp'));
                }

                $file_data = file_get_contents($_FILES['attachment']['tmp_name']);
                $attachment_base64 = base64_encode($file_data);
                $attachment_filename = $_FILES['attachment']['name'];
            }

            // Get phone numbers
            $recipients = []; // list of ['phone' => ..., 'name' => ...]

            if (!empty($selected_staff) && is_array($selected_staff)) {
                $this->db->select('staffid, firstname, lastname, phonenumber');
                $this->db->where_in('staffid', $selected_staff);
                $staff_members = $this->db->get(db_prefix() . 'staff')->result_array();
                foreach ($staff_members as $staff) {
                    if (!empty($staff['phonenumber'])) {
                        $recipients[] = [
                            'phone' => $staff['phonenumber'],
                            'name' => $staff['firstname'] . ' ' . $staff['lastname'] . ' (Staff)'
                        ];
                    }
                }
            }

            if (!empty($selected_leads) && is_array($selected_leads)) {
                $this->db->select('id, name, phonenumber');
                $this->db->where_in('id', $selected_leads);
                $leads = $this->db->get(db_prefix() . 'leads')->result_array();
                foreach ($leads as $lead) {
                    if (!empty($lead['phonenumber'])) {
                        $recipients[] = [
                            'phone' => $lead['phonenumber'],
                            'name' => $lead['name'] . ' (Lead)'
                        ];
                    }
                }
            }

            if (empty($recipients)) {
                set_alert('warning', 'No recipients with valid phone numbers were selected.');
                redirect(admin_url('new_updates_wp'));
            }

            $success_count = 0;
            $failed_count = 0;
            $errors = [];

            $promises = [];
            $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 120]);

            foreach ($recipients as $index => $recipient) {
                // Sanitize phone number
                $phone = preg_replace('/[^\d]/', '', $recipient['phone']);
                if (strlen($phone) === 10) {
                    $phone = '91' . $phone;
                }

                if (empty($phone)) {
                    $failed_count++;
                    $errors[] = "Invalid phone number for " . $recipient['name'];
                    continue;
                }

                $payload = [
                    'to' => $phone,
                    'message' => $message,
                    'type' => 'general'
                ];

                if (!empty($attachment_base64)) {
                    $payload['pdf'] = $attachment_base64;
                    $payload['filename'] = $attachment_filename;
                }

                $promises[$index] = $client->requestAsync('POST', 'https://2fa.tehub.in/api/whatsapp.php', [
                    'json' => $payload,
                    'headers' => [
                        'Authorization' => 'Bearer teh_api_47dbc4f2285eeadfcdc8b60edc25f4ae',
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ]
                ]);
            }

            if (!empty($promises)) {
                $results = \GuzzleHttp\Promise\Utils::settle($promises)->wait();

                foreach ($results as $index => $result) {
                    $recipient = $recipients[$index];
                    if ($result['state'] === 'fulfilled') {
                        $response = $result['value'];
                        $body = json_decode($response->getBody());
                        if (isset($body->success) && $body->success === true) {
                            $success_count++;
                        } else {
                            $failed_count++;
                            $error_msg = isset($body->error) ? $body->error : (isset($body->message) ? $body->message : 'Unknown error');
                            $errors[] = "Failed sending to " . $recipient['name'] . ": " . $error_msg;
                        }
                    } else {
                        $failed_count++;
                        $reason = $result['reason'];
                        $errors[] = "Failed sending to " . $recipient['name'] . ": " . $reason->getMessage();
                    }
                }
            }

            if ($success_count > 0) {
                set_alert('success', "Successfully sent update to {$success_count} recipient(s).");
            }
            if ($failed_count > 0) {
                set_alert('danger', "Failed sending to {$failed_count} recipient(s). Errors: " . implode(' | ', $errors));
            }

            redirect(admin_url('new_updates_wp'));
        }

        // Load staff members
        $this->db->select('staffid, firstname, lastname, phonenumber, email');
        $this->db->from(db_prefix() . 'staff');
        $this->db->where('active', 1);
        $data['staff'] = $this->db->get()->result_array();

        // Load leads
        $this->db->select('id, name, phonenumber, email, company');
        $this->db->from(db_prefix() . 'leads');
        $this->db->where('lost', 0);
        $this->db->where('junk', 0);
        $data['leads'] = $this->db->get()->result_array();

        $data['title'] = 'New updates WP';
        $this->load->view('admin/new_updates_wp/send_updates', $data);
    }
}
