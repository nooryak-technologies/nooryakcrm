<?php

defined('BASEPATH') || exit('No direct script access allowed');

require_once __DIR__.'/RestController.php';

use FlutexAdminApi\RestController;

class Leads extends RestController
{
    protected $staffInfo;

    public function __construct()
    {
        parent::__construct();
        register_language_files('flutex_admin_api');
        load_admin_language();
        
        $this->load->helper('flutex_admin_api');
        if (!isset(isAuthorized()['status'])) {
            $this->response(isAuthorized()['response'], isAuthorized()['response_code']);
        }

        $this->staffInfo = isAuthorized();

        if (staff_cant('view', 'leads', $this->staffInfo['data']->staff_id)) {
            $this->response(['message' => _l('not_permission_to_perform_this_action')], RestController::HTTP_FORBIDDEN);
        }
    }
    
    public function leads_get()
    {
        $leadID = $this->get('id');
        $limit = $this->get('limit');
        $offset = $this->get('offset');
        $sort = $this->get('sort');
        
        $where = [];
        $source = $this->get('source');
        if (isset($source)) {
            $where['source'] = $source;
        }
        $status = $this->get('status');
        if (isset($status)) {
            $where['status'] = $status;
        }

        $date_filter = $this->get('date_filter');
        if (isset($date_filter) && !empty($date_filter)) {
            $today = date('Y-m-d');
            if ($date_filter === 'today') {
                $where['DATE(dateadded)'] = $today;
            } else if ($date_filter === 'yesterday') {
                $where['DATE(dateadded)'] = date('Y-m-d', strtotime('-1 day'));
            } else if ($date_filter === 'tomorrow') {
                $where['DATE(dateadded)'] = date('Y-m-d', strtotime('+1 day'));
            } else if ($date_filter === 'week') {
                $where['DATE(dateadded) >='] = date('Y-m-d', strtotime('-7 days'));
                $where['DATE(dateadded) <='] = $today;
            }
        }
        $start_date = $this->get('start_date');
        $end_date = $this->get('end_date');
        if (isset($start_date) && !empty($start_date) && isset($end_date) && !empty($end_date)) {
            $where['DATE(dateadded) >='] = $start_date;
            $where['DATE(dateadded) <='] = $end_date;
        }
        
        $this->load->model('leads_api_model');
        
        $leadData = $this->leads_api_model->get($leadID,$where,$limit,$offset,$sort);
        
        if (!empty($leadData) && !empty($leadID)) {
            $this->response(['message' => _l('data_retrieved_successfully'), 'data' => $leadData], RestController::HTTP_OK);
        }
        
        $leads_summary = $this->leads_summary();
        
        if (!empty($leadData)) {
            $this->response(['message' => _l('data_retrieved_successfully'), 'data' => $leadData, 'overview' => $leads_summary], RestController::HTTP_OK);
        } else {
            $this->response(['message' => _l('data_not_found'), 'overview' => $leads_summary], RestController::HTTP_NOT_FOUND);
        }
    }

    public function leads_summary()
    {
        // Leads Overview
        $leads = [];
        $this->load->model('leads_model');
        $leads_statuses = $this->leads_model->get_status();

        foreach ($leads_statuses as $key => $status) {
            $where = 'status = ' . $status['id'];
            array_push($leads, [
                'status' => $status['name'],
                'total' => strval(total_rows(db_prefix() . 'leads', $where)),
                'percent' => total_rows(db_prefix() . 'leads', $where) == 0 ? '0' : strval(total_rows(db_prefix() . 'leads', $where) / total_rows(db_prefix() . 'leads') * 100)
            ]);
        }
        return $leads;
    }
    
    public function kanban_leads_get()
    {
        $this->load->model('leads_model');
        $leads_statuses = $this->leads_model->get_status();
        $leadData = [];
        foreach ($leads_statuses as $key => $status) {
            $where = 'status = ' . $status['id'];
            array_push($leadData, [
                'id'=> $status['id'],
                'status' => $status['name'],
                'color' => $status['color'],
                'total' => strval(total_rows(db_prefix() . 'leads', $where)),
                'leads' => $this->leads_model->get('',$where),
            ]);
        }
        
        if (!empty($leadData)) {
            $this->response(['message' => _l('data_retrieved_successfully'), 'data' => $leadData], RestController::HTTP_OK);
        } else {
            $this->response(['message' => _l('data_not_found')], RestController::HTTP_NOT_FOUND);
        }
    }
    
    public function search_get()
    {
        if (!empty($this->get()) && !in_array('search', array_keys($this->get()))) {
            $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
        }
        
        $keySearch = $this->get('search');
        
        $where = '';
        
        if ($keySearch) {
            $keySearch = trim(urldecode($keySearch));
            $keySearch = $this->db->escape_like_str($keySearch);
            $where .= '(leads.name LIKE "%' . $keySearch . '%" OR title LIKE "%' . $keySearch . '%" OR company LIKE "%' . $keySearch . '%"
                OR zip LIKE "%' . $keySearch . '%" OR city LIKE "%' . $keySearch . '%" OR state LIKE "%' . $keySearch . '%" OR leads.address LIKE "%' . $keySearch . '%"
                OR leads.email LIKE "%' . $keySearch . '%" OR leads.phonenumber LIKE "%' . $keySearch . '%")';
        }
        
        $this->load->model('leads_model');
        
        $leadData = $this->leads_model->get('', $where);
        
        if (!empty($leadData)) {
            $this->response(['message' => _l('data_retrieved_successfully'), 'data' => $leadData], RestController::HTTP_OK);
        } else {
            $this->response(['message' => _l('data_not_found')], RestController::HTTP_NOT_FOUND);
        }
    }
    
    public function notes_get()
    {
        if (!empty($this->get()) && !in_array('id', array_keys($this->get()))) {
            $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
        }
        
        $leadID = $this->get('id');
        
        $this->load->model('misc_model');
        $notes = $this->misc_model->get_notes($leadID, 'lead');
        $leadNotes = [];
        foreach ($notes as $note) {
            $leadNotes[] = array(
                'id' => $note['id'],
                'description' => $note['description'],
                'date_contacted' => $note['date_contacted'],
                'dateadded' => $note['dateadded'],
                'addedfrom' => $note['addedfrom'],
                'userid' => $note['staffid'],
                'firstname' => $note['firstname'],
                'lastname' => $note['lastname'],
                'email' => $note['email'],
                'phonenumber' => $note['phonenumber'],
                'active' => $note['active'],
                'profile_image' => staff_profile_image_url($note['addedfrom'])
            );
        }
        
        if (!empty($leadNotes)) {
            $this->response(['message' => _l('data_retrieved_successfully'), 'data' => $leadNotes], RestController::HTTP_OK);
        } else {
            $this->response(['message' => _l('data_not_found')], RestController::HTTP_NOT_FOUND);
        }
    }
    
    public function notes_post()
    {
        try {
            
            if (!empty($this->get()) && !in_array('id', array_keys($this->get()))) {
                $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
            }
            $this->form_validation->set_rules('description', 'Note', 'required');
            
            if (!$this->form_validation->run()) {
                $this->response(['message' => strip_tags(validation_errors()),'error' => $this->form_validation->error_array()], RestController::HTTP_BAD_REQUEST);
            } else {
                $leadID = $this->get('id');
                $data = [
                    'dateadded' => date('Y-m-d H:i:s'),
                    'addedfrom' => $this->staffInfo['data']->staff_id,
                    'rel_type' => 'lead',
                    'rel_id' => $leadID,
                    'description' => $this->input->post('description'),
                    'date_contacted' => $this->input->post('date_contacted') ?? null,
                ];
                $success = $this->db->insert(db_prefix() . 'notes', $data);
                if ($success) {
                    $this->response(['message' => _l('added_successfully', _l('note'))], RestController::HTTP_OK);
                } else {
                    $this->response(['message' => _l('add_failed', _l('note'))], RestController::HTTP_NOT_FOUND);
                }
            }
            
        } catch (\Throwable $th) {
            $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
        }
    }
    
    public function activity_log_get()
    {
        if (!empty($this->get()) && !in_array('id', array_keys($this->get()))) {
            $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
        }
        
        $leadID = $this->get('id');
        
        $this->load->model('leads_model');
        
        $activityLogData = $this->leads_model->get_lead_activity_log($leadID);
        $leadActivityLog = [];
        foreach ($activityLogData as $activityLog) {
            $additional_data = unserialize($activityLog['additional_data']);
            $leadActivityLog[] = array(
                'id' => $activityLog['id'],
                'leadid' => $activityLog['leadid'],
                'description' => $activityLog['description'],
                'additional_data' => _l($activityLog['description'], $additional_data),
                'date' => $activityLog['date'],
                'staffid' => $activityLog['staffid'],
                'full_name' => $activityLog['full_name'],
                'custom_activity' => $activityLog['custom_activity'],
            );
        }
        
        if (!empty($leadActivityLog)) {
            $this->response(['message' => _l('data_retrieved_successfully'), 'data' => $leadActivityLog], RestController::HTTP_OK);
        } else {
            $this->response(['message' => _l('data_not_found')], RestController::HTTP_NOT_FOUND);
        }
    }
    
    public function reminders_get()
    {
        if (!empty($this->get()) && !in_array('id', array_keys($this->get()))) {
            $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
        }
        
        $leadID = $this->get('id');
        
        $this->db->join(db_prefix() . 'staff', '' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'reminders.staff', 'left');
        $this->db->where(db_prefix() . 'reminders.rel_type', 'lead');
        $this->db->where(db_prefix() . 'reminders.rel_id', $leadID);
        $this->db->order_by('date', 'desc');
        
        $reminders = $this->db->get(db_prefix() . 'reminders')->result_array();
        $remindersData = [];
        foreach ($reminders as $reminder) {
            $remindersData[] = array(
                'id' => $reminder['id'],
                'description' => $reminder['description'],
                'rel_id' => $reminder['rel_id'],
                'rel_type' => $reminder['rel_type'],
                'isnotified' => $reminder['isnotified'],
                'date' => $reminder['date'],
                'staffid' => $reminder['staffid'],
                'creator' => $reminder['creator'],
                'notify_by_email' => $reminder['notify_by_email'],
            );
        }
        
        if (!empty($remindersData)) {
            $this->response(['message' => _l('data_retrieved_successfully'), 'data' => $remindersData], RestController::HTTP_OK);
        } else {
            $this->response(['message' => _l('data_not_found')], RestController::HTTP_NOT_FOUND);
        }
    }
    
    public function reminders_post()
    {
        try {
            
            if (!empty($this->get()) && !in_array('id', array_keys($this->get()))) {
                $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
            }
            $this->form_validation->set_rules('date', 'Date to be notified', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('staff', 'Staff', 'required');
            
            if (!$this->form_validation->run()) {
                $this->response(['message' => strip_tags(validation_errors()),'error' => $this->form_validation->error_array()], RestController::HTTP_BAD_REQUEST);
            } else {
                $leadID = $this->get('id');
                $data = [
                    'date' => $this->input->post('date'),
                    'creator' => $this->staffInfo['data']->staff_id,
                    'rel_type' => 'lead',
                    'rel_id' => $leadID,
                    'description' => $this->input->post('description'),
                    'staff' => $this->input->post('staff'),
                    'notify_by_email' => $this->input->post('notify_by_email') ?? null,
                ];
                $success = $this->db->insert(db_prefix() . 'reminders', $data);
                if ($success) {
                    $this->load->model('leads_model');
                    $this->leads_model->log_lead_activity($data['rel_id'], 'not_activity_new_reminder_created', false, serialize([
                        get_staff_full_name($data['staff']),_dt($data['date'])]));
                    log_activity('New Reminder Added [' . ucfirst($data['rel_type']) . 'ID: ' . $data['rel_id'] . ' Description: ' . $data['description'] . ']');
                    $this->response(['message' => _l('reminder_added_successfully')], RestController::HTTP_OK);
                } else {
                    $this->response(['message' => _l('add_failed', _l('reminder'))], RestController::HTTP_NOT_FOUND);
                }
            }
            
        } catch (\Throwable $th) {
            $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
        }
    }
    
    public function leads_post()
    {
        try {
            
            $this->form_validation->set_rules('name', 'Lead Name', 'required|max_length[600]');
            $this->form_validation->set_rules('source', 'Source', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');
            
            if (!$this->form_validation->run()) {
                $this->response(['message' => strip_tags(validation_errors()),'error' => $this->form_validation->error_array()], RestController::HTTP_BAD_REQUEST);
            } else {
                $data = [
                    'name' => $this->input->post('name'),
                    'source' => $this->input->post('source'),
                    'status' => $this->input->post('status'),
                    'assigned' => $this->input->post('assigned'),
                    'lead_value' => $this->input->post('lead_value'),
                    'tags' => $this->input->post('tags')??'',
                    'title' => $this->input->post('title')??'',
                    'email' => $this->input->post('email')??'',
                    'website' => $this->input->post('website')??'',
                    'phonenumber' => $this->input->post('phonenumber')??'',
                    'company' => $this->input->post('company')??'',
                    'address' => $this->input->post('address')??'',
                    'city' => $this->input->post('city')??'',
                    'zip' => $this->input->post('zip')??'',
                    'state' => $this->input->post('state')??'',
                    'default_language' => $this->input->post('default_language')??'',
                    'description' => $this->input->post('description')??'',
                    'is_public' => $this->input->post('is_public')??''
                ];
                
                $this->load->model('leads_model');
                $success = $this->leads_model->add($data);
                if ($success) {
                    $this->response(['message' => _l('lead_added_successfully')], RestController::HTTP_OK);
                } else {
                    $this->response(['message' => _l('lead_add_failed')], RestController::HTTP_NOT_FOUND);
                }
            }
            
        } catch (\Throwable $th) {
            $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
        }
    }
    
    public function leads_put()
    {
        try {
            
            if (!empty($this->get()) && !in_array('id', array_keys($this->get()))) {
                $this->response(['message' => _l('hhhh')], RestController::HTTP_BAD_REQUEST);
            }
            
            $leadID = $this->get('id');
            $this->load->model('leads_model');
            $lead = $this->leads_model->get($leadID);
            
            if (is_object($lead)) {
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $success = $this->leads_model->update($data, $leadID);
                if ($success) {
                    $this->response(['message' => _l('lead_updated_successfully')], RestController::HTTP_OK);
                } else {
                    $this->response(['message' => _l('lead_update_failed')], RestController::HTTP_NOT_FOUND);
                }
            } else {
                $this->response(['message' => _l('invalid_lead_id')], RestController::HTTP_NOT_FOUND);
            }
            
        } catch (\Throwable $th) {
            $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
        }
    }
    
    public function leads_delete()
    {
        
        $leadID = $this->get('id');
        
        if (staff_cant('delete', 'leads', $this->staffInfo['data']->staff_id)) {
            $this->response(['message' => _l('not_permission_to_perform_this_action')], RestController::HTTP_FORBIDDEN);
        }
        
        $this->load->model('leads_model');
        $lead = $this->leads_model->get($leadID);
        if (is_object($lead)) {
            $output = $this->leads_model->delete($leadID);
            if ($output === TRUE) {
                $this->response(['message' => _l('lead_deleted_successfully')], RestController::HTTP_OK);
            } else {
                $this->response(['message' => _l('lead_delete_failed')], RestController::HTTP_NOT_FOUND);
            }
        } else {
            $this->response(['message' => _l('invalid_lead_id')], RestController::HTTP_NOT_FOUND);
        }
    }

    public function voice_notes_get()
    {
        if (!empty($this->get()) && !in_array('id', array_keys($this->get()))) {
            $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
        }
        
        $leadID = $this->get('id');
        $table_name = db_prefix() . 'leads_chat_messages';
        $staff_table = db_prefix() . 'staff';
        
        // Auto-create table if not exists just in case
        $this->db->query("CREATE TABLE IF NOT EXISTS `$table_name` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `lead_id` INT NOT NULL,
            `sender_id` INT NOT NULL,
            `sender_role` VARCHAR(20) NOT NULL,
            `staff_id` INT DEFAULT 0,
            `message_type` VARCHAR(10) NOT NULL,
            `message` TEXT NOT NULL,
            `timestamp` DATETIME NOT NULL,
            KEY `lead_id` (`lead_id`),
            KEY `sender_id` (`sender_id`),
            KEY `staff_id` (`staff_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $this->db->select("m.*, CONCAT(s.firstname, ' ', s.lastname) as sender_name");
        $this->db->from("$table_name m");
        $this->db->join("$staff_table s", "s.staffid = m.sender_id", "left");
        $this->db->where('m.lead_id', $leadID);
        $this->db->order_by('m.timestamp', 'asc');
        $voice_notes = $this->db->get()->result_array();
        
        foreach ($voice_notes as &$note) {
            $note['formatted_time'] = _dt($note['timestamp']);
            $note['relative_time'] = time_ago($note['timestamp']);
            $note['avatar_url'] = staff_profile_image_url($note['sender_id'], 'small');
            if ($note['message_type'] === 'voice') {
                $note['message'] = base_url($note['message']);
            }
        }
        
        if (!empty($voice_notes)) {
            $this->response(['message' => _l('data_retrieved_successfully'), 'data' => $voice_notes], RestController::HTTP_OK);
        } else {
            $this->response(['message' => _l('data_not_found')], RestController::HTTP_NOT_FOUND);
        }
    }

    public function voice_notes_post()
    {
        try {
            if (!empty($this->get()) && !in_array('id', array_keys($this->get()))) {
                $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
            }
            
            $leadID = $this->get('id');
            $table_name = db_prefix() . 'leads_chat_messages';
            
            // Auto-create table if not exists just in case
            $this->db->query("CREATE TABLE IF NOT EXISTS `$table_name` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `lead_id` INT NOT NULL,
                `sender_id` INT NOT NULL,
                `sender_role` VARCHAR(20) NOT NULL,
                `staff_id` INT DEFAULT 0,
                `message_type` VARCHAR(10) NOT NULL,
                `message` TEXT NOT NULL,
                `timestamp` DATETIME NOT NULL,
                KEY `lead_id` (`lead_id`),
                KEY `sender_id` (`sender_id`),
                KEY `staff_id` (`staff_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // Check if it's a text message
            $message_text = $this->input->post('message');
            if (!empty($message_text)) {
                $data = [
                    'lead_id' => intval($leadID),
                    'sender_id' => $this->staffInfo['data']->staff_id,
                    'sender_role' => is_admin() ? 'admin' : 'staff',
                    'staff_id' => is_admin() ? 0 : $this->staffInfo['data']->staff_id,
                    'message_type' => 'text',
                    'message' => htmlspecialchars($message_text),
                    'timestamp' => date('Y-m-d H:i:s')
                ];
                if ($this->db->insert($table_name, $data)) {
                    $this->response(['message' => 'Message sent successfully'], RestController::HTTP_OK);
                } else {
                    $this->response(['message' => 'Failed to save to database'], RestController::HTTP_INTERNAL_ERROR);
                }
                return;
            }
            
            if (!isset($_FILES['audio'])) {
                $this->response(['message' => 'Missing audio file or message text'], RestController::HTTP_BAD_REQUEST);
            }
            
            $upload_dir = FCPATH . 'uploads/leads_chat';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $filename = 'audio_' . time() . '_' . rand(1000, 9999) . '.wav';
            $filepath = $upload_dir . '/' . $filename;
            
            if (move_uploaded_file($_FILES['audio']['tmp_name'], $filepath)) {
                $data = [
                    'lead_id' => intval($leadID),
                    'sender_id' => $this->staffInfo['data']->staff_id,
                    'sender_role' => is_admin() ? 'admin' : 'staff',
                    'staff_id' => is_admin() ? 0 : $this->staffInfo['data']->staff_id,
                    'message_type' => 'voice',
                    'message' => 'uploads/leads_chat/' . $filename,
                    'timestamp' => date('Y-m-d H:i:s')
                ];
                
                if ($this->db->insert($table_name, $data)) {
                    $this->response(['message' => 'Voice note uploaded successfully'], RestController::HTTP_OK);
                } else {
                    @unlink($filepath);
                    $this->response(['message' => 'Failed to save to database'], RestController::HTTP_INTERNAL_ERROR);
                }
            } else {
                $this->response(['message' => 'Audio upload failed'], RestController::HTTP_INTERNAL_ERROR);
            }
        } catch (\Throwable $th) {
            $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
        }
    }
}