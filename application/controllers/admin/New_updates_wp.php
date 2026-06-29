<?php

defined('BASEPATH') or exit('No direct script access allowed');

class New_updates_wp extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        
        // Auto-create history table if not exists
        $this->db->query("CREATE TABLE IF NOT EXISTS " . db_prefix() . "wp_history (
            id INT AUTO_INCREMENT PRIMARY KEY,
            message TEXT,
            attachment_name VARCHAR(255),
            recipients_count INT,
            success_count INT,
            failed_count INT,
            date_sent DATETIME
        )");
    }

    public function index()
    {
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

        // Load recent sends
        $this->db->order_by('date_sent', 'DESC');
        $data['history'] = $this->db->get(db_prefix() . 'wp_history')->result_array();

        $data['title'] = 'New updates WP';
        $this->load->view('admin/new_updates_wp/send_updates', $data);
    }

    public function upload_attachment()
    {
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
            $file_size = $_FILES['attachment']['size'];
            if ($file_size > 30 * 1024 * 1024) {
                echo json_encode(['success' => false, 'error' => 'File size exceeds the 30MB limit.']);
                return;
            }

            $dir = FCPATH . 'temp/wp_uploads/';
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '', $_FILES['attachment']['name']);
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $dir . $filename)) {
                echo json_encode([
                    'success' => true,
                    'temp_file' => $filename,
                    'original_name' => $_FILES['attachment']['name']
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to save uploaded file.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'No file uploaded.']);
        }
    }

    public function send_single()
    {
        $phone = $this->input->post('phone', true);
        $name = $this->input->post('name', true);
        $message = $this->input->post('message', true);
        $temp_file = $this->input->post('temp_file', true);
        $original_name = $this->input->post('original_name', true);

        // Sanitize phone number and trim leading zeros
        $phone = preg_replace('/[^\d]/', '', $phone);
        $phone = ltrim($phone, '0');
        if (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        if (empty($phone)) {
            echo json_encode(['success' => false, 'error' => 'Invalid phone number.']);
            return;
        }

        $attachment_base64 = null;
        if (!empty($temp_file)) {
            $filePath = FCPATH . 'temp/wp_uploads/' . $temp_file;
            if (file_exists($filePath)) {
                $attachment_base64 = base64_encode(file_get_contents($filePath));
            }
        }

        try {
            $payload = [
                'to' => $phone,
                'message' => $message,
                'type' => 'general'
            ];

            if (!empty($attachment_base64)) {
                $payload['pdf'] = $attachment_base64;
                $payload['filename'] = $original_name ?: 'Attachment';
            }

            $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 90]);
            $response = $client->request('POST', 'https://2fa.tehub.in/api/whatsapp.php', [
                'json' => $payload,
                'headers' => [
                    'Authorization' => 'Bearer teh_api_47dbc4f2285eeadfcdc8b60edc25f4ae',
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ]
            ]);

            $result = json_decode($response->getBody());

            if (isset($result->success) && $result->success === true) {
                echo json_encode(['success' => true]);
            } else {
                $error_msg = isset($result->error) ? $result->error : (isset($result->message) ? $result->message : 'Unknown error');
                echo json_encode(['success' => false, 'error' => $error_msg]);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function log_history()
    {
        $message = $this->input->post('message', true);
        $attachment_name = $this->input->post('attachment_name', true);
        $recipients_count = $this->input->post('recipients_count', true);
        $success_count = $this->input->post('success_count', true);
        $failed_count = $this->input->post('failed_count', true);
        $temp_file = $this->input->post('temp_file', true);

        // Delete temp file after broadcasting is finished
        if (!empty($temp_file)) {
            $filePath = FCPATH . 'temp/wp_uploads/' . $temp_file;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $this->db->insert(db_prefix() . 'wp_history', [
            'message' => $message,
            'attachment_name' => $attachment_name ?: '',
            'recipients_count' => $recipients_count,
            'success_count' => $success_count,
            'failed_count' => $failed_count,
            'date_sent' => date('Y-m-d H:i:s')
        ]);

        echo json_encode(['success' => true]);
    }
}
