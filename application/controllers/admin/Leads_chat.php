<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Leads_chat extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        
        // Only staff members are allowed
        if (!is_staff_member()) {
            ajax_access_denied();
        }

        // Initialize/Create table if not exists
        $this->init_db_table();
    }

    /**
     * Auto-creates the chat messages table in database
     */
    private function init_db_table()
    {
        $table_name = db_prefix() . 'leads_chat_messages';
        $this->db->query("CREATE TABLE IF NOT EXISTS `$table_name` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `lead_id` INT NOT NULL,
            `sender_id` INT NOT NULL,
            `sender_role` VARCHAR(20) NOT NULL, -- 'admin' or 'staff'
            `staff_id` INT DEFAULT 0,            -- 0 for global message, or specific staff member ID
            `message_type` VARCHAR(10) NOT NULL, -- 'text' or 'voice'
            `message` TEXT NOT NULL,             -- Text message or path/URL to the audio file
            `timestamp` DATETIME NOT NULL,
            KEY `lead_id` (`lead_id`),
            KEY `sender_id` (`sender_id`),
            KEY `staff_id` (`staff_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    /**
     * Get messages for a lead
     */
    public function get_messages()
    {
        $lead_id = $this->input->post('lead_id');
        if (!$lead_id) {
            echo json_encode(['success' => false, 'message' => 'Missing lead ID']);
            die;
        }

        $current_user_id = get_staff_user_id();
        $is_admin = is_admin();

        $table_name = db_prefix() . 'leads_chat_messages';
        $staff_table = db_prefix() . 'staff';

        $this->db->select("m.*, CONCAT(s.firstname, ' ', s.lastname) as sender_name");
        $this->db->from("$table_name m");
        $this->db->join("$staff_table s", "s.staffid = m.sender_id", "left");
        $this->db->where('m.lead_id', $lead_id);

        $last_message_id = $this->input->post('last_message_id');
        if ($last_message_id) {
            $this->db->where('m.id >', intval($last_message_id));
        }

        if (!$is_admin) {
            // Staff members can only see their own thread messages
            // This includes:
            // 1. Messages sent by themselves (sender_role = 'staff' AND sender_id = current_user)
            // 2. Messages sent by Admin targeted to them (sender_role = 'admin' AND staff_id = current_user)
            // 3. Global Admin messages (sender_role = 'admin' AND staff_id = 0)
            $this->db->where("((m.sender_role = 'staff' AND m.sender_id = $current_user_id) OR (m.sender_role = 'admin' AND (m.staff_id = $current_user_id OR m.staff_id = 0)))");
        } else {
            // Admin can see everything or filter by a specific staff member
            $staff_filter_id = $this->input->post('staff_id');
            if ($staff_filter_id && $staff_filter_id != 'unified') {
                $staff_filter_id = intval($staff_filter_id);
                // Admin filters to show a specific staff member's thread
                // This includes:
                // 1. Messages sent by that staff member
                // 2. Messages sent by Admin targeted to that staff member
                // 3. Global Admin messages (sender_role = 'admin' AND staff_id = 0)
                $this->db->where("((m.sender_role = 'staff' AND m.sender_id = $staff_filter_id) OR (m.sender_role = 'admin' AND (m.staff_id = $staff_filter_id OR m.staff_id = 0)))");
            }
        }

        $this->db->order_by('m.timestamp', 'asc');
        $messages = $this->db->get()->result_array();

        // Process message list and add relative/formatted date-times
        foreach ($messages as &$msg) {
            $msg['formatted_time'] = _dt($msg['timestamp']);
            $msg['relative_time'] = time_ago($msg['timestamp']);
            $msg['avatar_url'] = staff_profile_image_url($msg['sender_id'], 'small');
            if ($msg['message_type'] === 'voice') {
                $msg['message'] = base_url($msg['message']);
            }
        }

        $response = [
            'success' => true,
            'messages' => $messages,
            'current_user_id' => $current_user_id,
            'is_admin' => $is_admin
        ];

        // If admin, return list of active staff members to populate the dropdown filters
        if ($is_admin) {
            $this->db->select('staffid, firstname, lastname');
            $this->db->where('active', 1);
            $response['staff_members'] = $this->db->get($staff_table)->result_array();
        }

        echo json_encode($response);
        die;
    }

    /**
     * Send text message
     */
    public function send_message()
    {
        $lead_id = $this->input->post('lead_id');
        $message = $this->input->post('message');

        if (!$lead_id || empty(trim($message))) {
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            die;
        }

        $current_user_id = get_staff_user_id();
        $is_admin = is_admin();

        // Determine thread scoping/targeting
        if ($is_admin) {
            // Admin target staff thread (defaults to 0 for unified/global message)
            $staff_id = $this->input->post('staff_id');
            $staff_id = ($staff_id && $staff_id !== 'unified') ? intval($staff_id) : 0;
        } else {
            // Staff messages always target their own thread
            $staff_id = $current_user_id;
        }

        $data = [
            'lead_id' => intval($lead_id),
            'sender_id' => $current_user_id,
            'sender_role' => $is_admin ? 'admin' : 'staff',
            'staff_id' => $staff_id,
            'message_type' => 'text',
            'message' => htmlspecialchars($message),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $table_name = db_prefix() . 'leads_chat_messages';
        if ($this->db->insert($table_name, $data)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save message']);
        }
        die;
    }

    /**
     * Upload and record voice message
     */
    public function send_voice_message()
    {
        $lead_id = $this->input->post('lead_id');
        if (!$lead_id || !isset($_FILES['audio'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            die;
        }

        $current_user_id = get_staff_user_id();
        $is_admin = is_admin();

        // Create upload directory if it does not exist
        $upload_dir = FCPATH . 'uploads/leads_chat';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate a clean secure random filename
        $filename = 'audio_' . time() . '_' . rand(1000, 9999) . '.wav';
        $filepath = $upload_dir . '/' . $filename;

        if (move_uploaded_file($_FILES['audio']['tmp_name'], $filepath)) {
            // Thread targeting
            if ($is_admin) {
                $staff_id = $this->input->post('staff_id');
                $staff_id = ($staff_id && $staff_id !== 'unified') ? intval($staff_id) : 0;
            } else {
                $staff_id = $current_user_id;
            }

            $data = [
                'lead_id' => intval($lead_id),
                'sender_id' => $current_user_id,
                'sender_role' => $is_admin ? 'admin' : 'staff',
                'staff_id' => $staff_id,
                'message_type' => 'voice',
                'message' => 'uploads/leads_chat/' . $filename, // Relative path stored in database
                'timestamp' => date('Y-m-d H:i:s')
            ];

            $table_name = db_prefix() . 'leads_chat_messages';
            if ($this->db->insert($table_name, $data)) {
                echo json_encode(['success' => true]);
            } else {
                // Cleanup file if DB insert failed
                @unlink($filepath);
                echo json_encode(['success' => false, 'message' => 'Database save failed']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Audio upload failed']);
        }
        die;
    }
}
