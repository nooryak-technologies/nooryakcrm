<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        hooks()->do_action('clients_authentication_constructor', $this);
    }

    public function index()
    {
        $this->login();
    }

    // Added for backward compatibilies
    public function admin()
    {
        redirect(admin_url('authentication'));
    }

    public function login()
    {
        if (is_client_logged_in()) {
            redirect(site_url());
        }

        $this->disableNavigation();

        hooks()->add_action('app_customers_head', static function () {
            echo '<link rel="icon" type="image/png" href="' . base_url('img/logo_icon.png') . '">' . "\n";
            echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
            echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
            echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">' . "\n";
            echo '<link rel="stylesheet" href="' . base_url('assets/css/customers-login.css?v=' . filemtime(FCPATH . 'assets/css/customers-login.css')) . '">' . "\n";
        });

        $this->form_validation->set_rules('password', _l('clients_login_password'), 'required');
        $this->form_validation->set_rules('email', _l('clients_login_email'), 'trim|required|valid_email');

        if (show_recaptcha_in_customers_area()) {
            $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
        }
        if ($this->form_validation->run() !== false) {
            $this->load->model('Authentication_model');

            $success = $this->Authentication_model->login(
                $this->input->post('email'),
                $this->input->post('password', false),
                $this->input->post('remember'),
                false
            );

            if (is_array($success) && isset($success['memberinactive'])) {
                set_alert('danger', _l('inactive_account'));
                redirect(site_url('authentication/login'));
            } elseif ($success == false) {
                set_alert('danger', _l('client_invalid_username_or_password'));
                redirect(site_url('authentication/login'));
            }

            if ($this->input->post('language') && $this->input->post('language') != '') {
                set_contact_language($this->input->post('language'));
            }

            $this->load->model('announcements_model');
            $this->announcements_model->set_announcements_as_read_except_last_one(get_contact_user_id());

            hooks()->do_action('after_contact_login');

            maybe_redirect_to_previous_url();
            redirect(site_url());
        }
        if (get_option('allow_registration') == 1) {
            $data['title'] = _l('clients_login_heading_register');
        } else {
            $data['title'] = _l('clients_login_heading_no_register');
        }
        $data['bodyclass'] = 'customers_login';

        $this->data($data);
        $this->view('login');
        $this->layout();
    }

    public function register()
    {
        if (get_option('allow_registration') != 1 || is_client_logged_in()) {
            redirect(site_url());
        }

        $ny_logo_icon = base_url('media/master/public/page_builder/pages/softland/assets/landingpage/image/logo_icon.png');
        hooks()->add_action('app_customers_head', static function () use ($ny_logo_icon) {
            echo '<link rel="icon" type="image/png" href="' . e($ny_logo_icon) . '">';
            echo '<link rel="apple-touch-icon" href="' . e($ny_logo_icon) . '">';
        });

        $requiredFields = get_required_fields_for_registration();
       
        $honeypot = get_option('enable_honeypot_spam_validation') == 1;

        $fields = [
            'firstname' => $honeypot ? 'firstnamemjxw' : 'firstname',
            'lastname'  => $honeypot ? 'lastnamemjxw' : 'lastname',
            'email'     => $honeypot ? 'emailmjxw' : 'email',
            'company'   => $honeypot ? 'companymjxw' : 'company',
        ];

        if (get_option('company_is_required') == 1) {
            $this->form_validation->set_rules($fields['company'], _l('client_company'), 'required');
        }

        $emailRules = 'trim|is_unique[' . db_prefix() . 'contacts.email]|valid_email';

        foreach(['contact', 'company'] as $fieldsKey) {
            foreach($requiredFields[$fieldsKey] as $key => $field) {
                $formKey = strafter($key, '_');

                if(isset($fields[$formKey])) {
                    $formKey = $fields[$formKey];
                }
                
                if ($key === 'company_country' || $key === 'company_address') {
                    continue;
                }

                if($key !== 'contact_email'){
                    if($field['is_required']) {
                        $this->form_validation->set_rules($formKey, $field['label'], 'required');
                    }
                } else {
                    if($field['is_required']) {
                        $emailRules .= '|required';
                    }

                    $this->form_validation->set_rules($formKey, $field['label'], $emailRules);
                }
            }
        }

        if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions') == 1) {
            $this->form_validation->set_rules(
                'accept_terms_and_conditions',
                _l('terms_and_conditions'),
                'required',
                ['required' => _l('terms_and_conditions_validation')]
            );
        }
       
        $this->form_validation->set_rules('password', _l('clients_register_password'), 'required');
        $this->form_validation->set_rules('passwordr', _l('customer_reset_password_repeat'), 'required|matches[password]');

        if (show_recaptcha_in_customers_area()) {
            $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
        }

        $custom_fields = get_custom_fields('customers', [
            'show_on_client_portal' => 1,
            'required'              => 1,
        ]);

        $custom_fields_contacts = get_custom_fields('contacts', [
            'show_on_client_portal' => 1,
            'required'              => 1,
        ]);

        foreach ($custom_fields as $field) {
            $field_name = 'custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']';
            if ($field['type'] == 'checkbox' || $field['type'] == 'multiselect') {
                $field_name .= '[]';
            }
            $this->form_validation->set_rules($field_name, $field['name'], 'required');
        }

        foreach ($custom_fields_contacts as $field) {
            $field_name = 'custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']';
            if ($field['type'] == 'checkbox' || $field['type'] == 'multiselect') {
                $field_name .= '[]';
            }
            $this->form_validation->set_rules($field_name, $field['name'], 'required');
        }

        if ($this->input->post()) {
            if ($honeypot &&
                count(array_filter($this->input->post(['email', 'firstname', 'lastname', 'company']))) > 0) {
                show_404();
            }

            $this->form_validation->set_rules('phonenumber', 'Phone Number', 'callback_verify_otp_session_match');

            if ($this->form_validation->run() !== false) {
                $data      = $this->input->post();
                $countryId = isset($data['country']) && is_numeric($data['country']) ? $data['country'] : 0;

                if (!isset($data['country'])) {
                    $data['country'] = 0;
                }

                if (is_automatic_calling_codes_enabled()) {
                    $customerCountry = get_country($countryId);

                    if ($customerCountry) {
                        $callingCode = '+' . ltrim($customerCountry->calling_code, '+');

                        if (startsWith($data['contact_phonenumber'], $customerCountry->calling_code)) { // with calling code but without the + prefix
                            $data['contact_phonenumber'] = '+' . $data['contact_phonenumber'];
                        } elseif (!startsWith($data['contact_phonenumber'], $callingCode)) {
                            $data['contact_phonenumber'] = $callingCode . $data['contact_phonenumber'];
                        }
                    }
                }

                define('CONTACT_REGISTERING', true);

                $clientid = $this->clients_model->add([
                      'billing_street'      => $data['address'],
                      'billing_city'        => $data['city'],
                      'billing_state'       => $data['state'],
                      'billing_zip'         => $data['zip'],
                      'billing_country'     => $countryId,
                      'firstname'           => $data[$fields['firstname']],
                      'lastname'            => $data[$fields['lastname']],
                      'email'               => $data[$fields['email']],
                      'contact_phonenumber' => $data['contact_phonenumber'] ,
                      'website'             => $data['website'],
                      'title'               => $data['title'],
                      'password'            => $data['passwordr'],
                      'company'             => $data[$fields['company']],
                      'vat'                 => isset($data['vat']) ? $data['vat'] : '',
                      'phonenumber'         => $data['phonenumber'],
                      'country'             => $data['country'],
                      'city'                => $data['city'],
                      'address'             => $data['address'],
                      'zip'                 => $data['zip'],
                      'state'               => $data['state'],
                      'custom_fields'       => isset($data['custom_fields']) && is_array($data['custom_fields']) ? $data['custom_fields'] : [],
                      'default_language'    => (get_contact_language() != '') ? get_contact_language() : get_option('active_language'),
                ], true);

                if ($clientid) {
                    $this->session->unset_userdata('mobile_otp_code');
                    $this->session->unset_userdata('mobile_otp_phone');
                    $this->session->unset_userdata('mobile_otp_expiry');
                    $this->session->unset_userdata('mobile_otp_verified');
                    $this->session->unset_userdata('mobile_otp_verified_phone');

                    hooks()->do_action('after_client_register', $clientid);

                    if (get_option('customers_register_require_confirmation') == '1') {
                        send_customer_registered_email_to_administrators($clientid);

                        $this->clients_model->require_confirmation($clientid);
                        set_alert('success', _l('customer_register_account_confirmation_approval_notice'));
                        redirect(site_url('authentication/login'));
                    }

                    $this->load->model('authentication_model');

                    $logged_in = $this->authentication_model->login(
                        $data[$fields['email']],
                        $this->input->post('password', false),
                        false,
                        false
                    );

                    $redUrl = site_url();

                    if ($logged_in) {
                        hooks()->do_action('after_client_register_logged_in', $clientid);
                        set_alert('success', _l('clients_successfully_registered'));
                    } else {
                        set_alert('warning', _l('clients_account_created_but_not_logged_in'));
                        $redUrl = site_url('authentication/login');
                    }

                    send_customer_registered_email_to_administrators($clientid);
                    redirect($redUrl);
                }
            }
        }

        $data['requiredFields'] = $requiredFields;
        $data['title']     = _l('clients_register_heading');
        $data['bodyclass'] = 'register';
        $data['honeypot']  = $honeypot;
        $data['fields']    = $fields;
        $this->data($data);
        $this->view('register');
        $this->layout();
    }

    public function forgot_password()
    {
        if (is_client_logged_in()) {
            redirect(site_url());
        }

        $this->form_validation->set_rules(
            'email',
            _l('customer_forgot_password_email'),
            'trim|required|valid_email|callback_contact_email_exists'
        );

        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $this->load->model('Authentication_model');
                $success = $this->Authentication_model->forgot_password($this->input->post('email'));
                if (is_array($success) && isset($success['memberinactive'])) {
                    set_alert('danger', _l('inactive_account'));
                } elseif ($success == true) {
                    set_alert('success', _l('check_email_for_resetting_password'));
                } else {
                    set_alert('danger', _l('error_setting_new_password_key'));
                }
                redirect(site_url('authentication/forgot_password'));
            }
        }
        $data['title'] = _l('customer_forgot_password');
        $this->data($data);
        $this->view('forgot_password');

        $this->layout();
    }

    public function reset_password($staff, $userid, $new_pass_key)
    {
        $this->load->model('Authentication_model');
        if (!$this->Authentication_model->can_reset_password($staff, $userid, $new_pass_key)) {
            set_alert('danger', _l('password_reset_key_expired'));
            redirect(site_url('authentication/login'));
        }

        $this->form_validation->set_rules('password', _l('customer_reset_password'), 'required');
        $this->form_validation->set_rules('passwordr', _l('customer_reset_password_repeat'), 'required|matches[password]');
        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                hooks()->do_action('before_user_reset_password', [
                    'staff'  => $staff,
                    'userid' => $userid,
                ]);
                $success = $this->Authentication_model->reset_password(
                    0,
                    $userid,
                    $new_pass_key,
                    $this->input->post('passwordr', false)
                );
                if (is_array($success) && $success['expired'] == true) {
                    set_alert('danger', _l('password_reset_key_expired'));
                } elseif ($success == true) {
                    hooks()->do_action('after_user_reset_password', [
                        'staff'  => $staff,
                        'userid' => $userid,
                    ]);
                    set_alert('success', _l('password_reset_message'));
                } else {
                    set_alert('danger', _l('password_reset_message_fail'));
                }
                redirect(site_url('authentication/login'));
            }
        }
        $data['title'] = _l('admin_auth_reset_password_heading');
        $this->data($data);
        $this->view('reset_password');
        $this->layout();
    }

    public function logout()
    {
        $this->load->model('authentication_model');
        $this->authentication_model->logout(false);
        hooks()->do_action('after_client_logout');
        redirect(site_url('authentication/login'));
    }

    public function contact_email_exists($email = '')
    {
        $this->db->where('email', $email);
        $total_rows = $this->db->count_all_results(db_prefix() . 'contacts');

        if ($total_rows == 0) {
            $this->form_validation->set_message('contact_email_exists', _l('auth_reset_pass_email_not_found'));

            return false;
        }

        return true;
    }

    public function recaptcha($str = '')
    {
        return do_recaptcha_validation($str);
    }

    public function change_language($lang = '')
    {
        if (is_language_disabled()) {
            redirect(site_url());
        }

        set_contact_language($lang);

        redirect(previous_url() ?: $_SERVER['HTTP_REFERER']);
    }

    public function send_otp()
    {
        $phone = trim($this->input->post('phone', true));
        if (empty($phone)) {
            echo json_encode(['success' => false, 'message' => 'Phone number is required.']);
            return;
        }

        // Check if phone number already exists in contacts
        $this->db->group_start();
        $this->db->where('phonenumber', $phone);
        $this->db->or_where('contact_phonenumber', $phone);
        $this->db->group_end();
        $total_rows = $this->db->count_all_results(db_prefix() . 'contacts');
        if ($total_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'This phone number is already registered.']);
            return;
        }

        // Generate 6 digit OTP
        $otp = (string)mt_rand(100000, 999999);

        // Try to load SMS library/gateway
        $this->load->helper('sms_helper');
        $active_gateway = $this->app_sms->get_active_gateway();
        $sms_sent = false;
        $error_msg = '';

        if ($active_gateway) {
            $gateway_class = 'sms_' . $active_gateway['id'];
            $gateway = $this->{$gateway_class};
            $message = "Your OTP verification code for Nooryak CRM is: " . $otp;
            $sms_sent = $gateway->send($phone, $message);
            if (!$sms_sent && isset($GLOBALS['sms_error'])) {
                $error_msg = $GLOBALS['sms_error'];
            }
        } else {
            // For testing/fallback when no active gateway is found, log it and return success.
            log_activity("OTP generated for registration: " . $otp . " to " . $phone . " (No active SMS gateway configured)");
            $sms_sent = true; 
        }

        if ($sms_sent) {
            $this->session->set_userdata('mobile_otp_code', $otp);
            $this->session->set_userdata('mobile_otp_phone', $phone);
            $this->session->set_userdata('mobile_otp_expiry', time() + 300); // 5 minutes validity
            
            $resp = ['success' => true, 'message' => 'OTP has been sent to your mobile number.'];
            if (ENVIRONMENT === 'development' || !$active_gateway) {
                $resp['debug_otp'] = $otp;
            }
            echo json_encode($resp);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send OTP. ' . $error_msg]);
        }
    }

    public function verify_otp()
    {
        $phone = trim($this->input->post('phone', true));
        $otp = trim($this->input->post('otp', true));

        if (empty($phone) || empty($otp)) {
            echo json_encode(['success' => false, 'message' => 'Phone number and OTP are required.']);
            return;
        }

        $session_otp = $this->session->userdata('mobile_otp_code');
        $session_phone = $this->session->userdata('mobile_otp_phone');
        $session_expiry = $this->session->userdata('mobile_otp_expiry');

        if (empty($session_otp) || empty($session_phone) || empty($session_expiry)) {
            echo json_encode(['success' => false, 'message' => 'No OTP request found. Please resend.']);
            return;
        }

        if (time() > $session_expiry) {
            echo json_encode(['success' => false, 'message' => 'OTP has expired. Please request a new one.']);
            return;
        }

        if ($session_otp !== $otp || $session_phone !== $phone) {
            echo json_encode(['success' => false, 'message' => 'Invalid OTP code. Please check and try again.']);
            return;
        }

        // Success
        $this->session->set_userdata('mobile_otp_verified', true);
        $this->session->set_userdata('mobile_otp_verified_phone', $phone);

        echo json_encode(['success' => true, 'message' => 'Mobile number verified successfully.']);
    }

    public function verify_otp_session_match($str)
    {
        $phone = $this->input->post('phonenumber');
        if ($this->session->userdata('mobile_otp_verified') !== true || $this->session->userdata('mobile_otp_verified_phone') !== $phone) {
            $this->form_validation->set_message('verify_otp_session_match', 'Please verify your mobile number via OTP first.');
            return false;
        }
        return true;
    }
}
