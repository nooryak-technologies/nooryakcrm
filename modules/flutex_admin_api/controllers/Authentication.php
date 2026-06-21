<?php

defined('BASEPATH') || exit('No direct script access allowed');

require_once __DIR__.'/RestController.php';
require_once __DIR__.'/../vendor/autoload.php';
use FlutexAdminApi\RestController;

class Authentication extends RestController
{
    
    public function __construct()
    {
        parent::__construct();
        register_language_files('flutex_admin_api');
        load_admin_language();

        $this->load->helper('flutex_admin_api');
        if (checkModuleStatus()) {
            $this->response(checkModuleStatus()['response'], checkModuleStatus()['response_code']);
        }
    }
    
    public function login_post()
    {
        if (1 != get_option('allow_flutex_admin_login')) {
            $this->response(['message' => _l('login_not_enabled_using_api')], RestController::HTTP_OK);
        }

        $requiredData = [
            'email'    => '',
            'password' => '',
        ];

        $postData = $this->post();
        $postData = array_merge($requiredData, $postData);

        $this->load->library('form_validation');

        $this->form_validation->set_data($postData);

        $this->form_validation->set_rules('email', _l('admin_auth_login_email'), 'trim|required|valid_email');
        $this->form_validation->set_rules('password', _l('admin_auth_login_password'), 'required');

        if (!$this->form_validation->run()) {
            $this->response(['message' => strip_tags(validation_errors())], RestController::HTTP_BAD_REQUEST);
        }

        try {
            $this->load->model('Authentication_model');

            $success = $this->Authentication_model->login($postData['email'], $postData['password'], true, true);
    
            if (is_array($success) && isset($success['memberinactive'])) {
                $this->response(['message' => _l('admin_auth_inactive_account')], RestController::HTTP_FORBIDDEN);
            } elseif (false == $success) {
                $this->response(['message' => _l('admin_auth_invalid_email_or_password')], RestController::HTTP_UNAUTHORIZED);
            }
    
            $table = db_prefix().'staff';
    
            $this->db->where('email', $postData['email']);
            $staff = $this->db->get($table)->row();
    
            $data = [
                'staff_id'         => $staff->staffid, // Staff ID
                'staff_email'      => $staff->email,   // Staff Email
                'staff_logged_in'  => true,
                'API_TIME'         => time(),
            ];
            
            $token         = $this->authorization_token->generateToken($data);
            $data['token'] = $token;
    
            $this->db->update(db_prefix() . 'staff', ['flutex_api_key' => $token], ['staffid' => $staff->staffid]);
    
            $this->response(['message' => _l('logged_in_successfully'), 'data' => $data], RestController::HTTP_OK);
            
        } catch (\Throwable $th) {
            $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
        }
    }
    
    public function forgotPassword_post()
    {
        try {
            $this->form_validation->set_rules('email', _l('admin_auth_login_email'), 'required|valid_email|callback_email_exists');
            
            if (!$this->form_validation->run()) {
                $this->response(['message' => strip_tags(validation_errors()),'error' => $this->form_validation->error_array()], RestController::HTTP_BAD_REQUEST);
            } else {
                $email = $this->input->post('email');
                
                $this->load->model('Authentication_model');
                $success = $this->Authentication_model->forgot_password($email, true);
                
                if (is_array($success) && isset($success['memberinactive'])) {
                    $this->response(['message' => _l('inactive_account')], RestController::HTTP_FORBIDDEN);
                } elseif ($success) {
                    $this->response(['message' => _l('check_email_for_resetting_password')], RestController::HTTP_OK);
                } else {
                    $this->response(['message' => _l('error_setting_new_password_key')], RestController::HTTP_INTERNAL_ERROR);
                }
            }
        } catch (\Throwable $th) {
            $this->response(['message' => _l('something_went_wrong')], RestController::HTTP_INTERNAL_ERROR);
        }
    }

    public function email_exists($email)
    {
        $total_rows = total_rows(db_prefix() . 'staff', [
            'email' => $email,
        ]);
        if ($total_rows == 0) {
            $this->form_validation->set_message('email_exists', _l('auth_reset_pass_email_not_found'));

            return false;
        }

        return true;
    }

    public function lookup_post()
    {
        $requiredData = [
            'email' => '',
        ];

        $postData = $this->post();
        $postData = array_merge($requiredData, $postData);

        if (empty($postData['email'])) {
            $this->response(['message' => 'Email is required'], RestController::HTTP_BAD_REQUEST);
        }

        $email = $postData['email'];

        // 1. Check if the user exists in the master database
        $this->db->where('email', $email);
        $master_staff = $this->db->get(db_prefix() . 'staff')->row();
        if ($master_staff) {
            $this->response([
                'status' => true,
                'domain' => APP_BASE_URL_DEFAULT,
                'is_tenant' => false
            ], RestController::HTTP_OK);
        }

        // 2. Load SaaS model and check all tenants
        if ($this->db->table_exists(db_prefix() . 'perfex_saas_companies')) {
            $this->load->model('perfex_saas/perfex_saas_model');
            $this->load->helper('perfex_saas/perfex_saas');
            $companies = $this->perfex_saas_model->companies();
            
            foreach ($companies as $company) {
                $dsn = $company->dsn;
                $db_prefix = perfex_saas_tenant_db_prefix($company->slug);
                $table = $db_prefix . 'staff';
                
                $query = "SELECT staffid FROM `$table` WHERE email = :email LIMIT 1";
                $params = [':email' => $email];
                
                try {
                    $result = perfex_saas_raw_query($query, $dsn, true, false, null, false, false, $params);
                    if (!empty($result) && reset($result) !== false) {
                        $tenant_url = perfex_saas_tenant_base_url($company);
                        $tenant_url = str_ireplace('.www.', '.', $tenant_url);
                        $this->response([
                            'status' => true,
                            'domain' => $tenant_url,
                            'is_tenant' => true,
                            'slug' => $company->slug
                        ], RestController::HTTP_OK);
                    }
                } catch (\Throwable $e) {
                    $this->response(['message' => 'Error on company ' . $company->slug . ': ' . $e->getMessage()], RestController::HTTP_INTERNAL_ERROR);
                }
            }
        }

        $this->response(['message' => 'User not found: (' . $email . ')'], RestController::HTTP_NOT_FOUND);
    }
}
