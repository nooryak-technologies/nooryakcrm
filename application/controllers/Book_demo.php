<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Book_demo extends App_Controller
{
    public function index()
    {
        $data = $this->prepare_view_data();

        if ($this->input->post()) {
            $this->process_submission($data);
        }

        $this->load->view('book_demo/index', $data);
    }

    public function recaptcha($str = '')
    {
        return do_recaptcha_validation($str);
    }

    private function prepare_view_data()
    {
        $softlandAssets = base_url('media/master/public/page_builder/pages/softland/assets/');

        return [
            'title'              => 'Schedule a Personalized Demo – ' . (get_option('companyname') ?: 'Nooryak CRM'),
            'softland_assets'    => $softlandAssets,
            'logo_url'           => $softlandAssets . 'landingpage/image/crm_logo.png',
            'favicon_url'        => $softlandAssets . 'landingpage/image/logo_icon.png',
            'home_url'           => site_url(),
            'login_url'          => site_url('authentication/login'),
            'register_url'       => site_url('authentication/register'),
            'form_action'        => site_url('book-a-demo'),
            'demo_image_url'     => base_url('img/getademo_image.png'),
            'show_recaptcha'     => show_recaptcha(),
            'recaptcha_site_key' => get_option('recaptcha_site_key'),
            'success'            => false,
            'error_message'      => '',
            'posted'             => [],
        ];
    }

    private function process_submission(array &$data)
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required|max_length[200]');
        $this->form_validation->set_rules('email', 'Business Email', 'trim|required|valid_email|max_length[200]');
        $this->form_validation->set_rules('phone', 'Phone Number', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('company', 'Company Name', 'trim|required|max_length[200]');
        $this->form_validation->set_rules('num_users', 'Number of Users', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('industry', 'Industry', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('requirements', 'Your Requirements', 'trim|required|max_length[5000]');

        if ($data['show_recaptcha']) {
            $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
        }

        $data['posted'] = [
            'full_name'    => $this->input->post('full_name', true),
            'email'        => $this->input->post('email', true),
            'phone'        => $this->input->post('phone', true),
            'company'      => $this->input->post('company', true),
            'num_users'    => $this->input->post('num_users', true),
            'industry'     => $this->input->post('industry', true),
            'requirements' => $this->input->post('requirements', true),
        ];

        if ($this->form_validation->run() === false) {
            return;
        }

        $payload = $data['posted'];
        $payload['submitted_at'] = date('Y-m-d H:i:s');
        $payload['ip_address']   = $this->input->ip_address();

        $message = $this->load->view('book_demo/email/demo_request', ['submission' => $payload], true);

        $this->load->model('emails_model');

        $recipient = $this->get_notification_email();
        $subject   = 'New Demo Request – ' . $payload['company'] . ' (' . $payload['full_name'] . ')';

        hooks()->add_filter('before_send_simple_email', function ($cnf) use ($payload) {
            $cnf['reply_to'] = $payload['email'];

            return $cnf;
        });

        $sent = $this->emails_model->send_simple_email($recipient, $subject, $message);

        if ($sent) {
            hooks()->do_action('book_demo_request_submitted', $payload);
            $data['success'] = true;
            $data['posted']  = [];
        } else {
            $data['error_message'] = 'We could not send your request right now. Please try again or contact us at sales@nooryakcrm.com.';
        }
    }

    private function get_notification_email()
    {
        $email = get_option('book_demo_notification_email');

        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        $smtp = get_option('smtp_email');
        if (!empty($smtp) && filter_var($smtp, FILTER_VALIDATE_EMAIL)) {
            return $smtp;
        }

        return 'sales@nooryakcrm.com';
    }
}
