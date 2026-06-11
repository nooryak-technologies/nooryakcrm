<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Privacy_policy extends ClientsController
{
    public function index()
    {
        $this->disableNavigation();
        $this->disableFooter();

        $this->load->helper('nooryak');
        nooryak_enqueue_public_nav_assets();

        hooks()->add_action('app_customers_head', static function () {
            $legalCss = FCPATH . 'assets/css/legal-pages.css';
            echo '<link rel="stylesheet" href="' . base_url('assets/css/legal-pages.css?v=' . (file_exists($legalCss) ? filemtime($legalCss) : time())) . '">' . "\n";
        }, 99);

        $policy = get_option('privacy_policy');
        $hasCustom = $this->hasMeaningfulContent($policy);

        $data = [
            'title'              => _l('privacy_policy') . ' – NOORYAKCRM',
            'bodyclass'          => 'customers_legal nooryak-public-nav',
            'use_custom_content' => $hasCustom,
            'custom_content'     => $hasCustom ? $policy : '',
            'last_updated'       => 'May 20, 2026',
            'back_url'           => site_url('authentication/register'),
        ];

        $this->data($data);
        $this->view('privacy_policy');
        $this->layout();
    }

    private function hasMeaningfulContent($html)
    {
        if (empty($html)) {
            return false;
        }

        return strlen(trim(strip_tags($html))) > 20;
    }
}
