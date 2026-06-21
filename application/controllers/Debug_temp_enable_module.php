<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Debug_temp_enable_module extends CI_Controller {
    public function index() {
        if ($this->db->table_exists(db_prefix() . 'perfex_saas_companies')) {
            $this->load->model('perfex_saas/perfex_saas_model');
            $this->load->helper('perfex_saas/perfex_saas');
            
            $company = $this->perfex_saas_model->get_company_by_slug('ahamath');
            if ($company) {
                try {
                    perfex_saas_impersonate_instance($company, function() {
                        $CI = &get_instance();
                        $CI->load->library('app_modules');
                        if ($CI->app_modules->activate('flutex_admin_api')) {
                            echo "Successfully activated flutex_admin_api module for ahamath!\n";
                        } else {
                            echo "Failed to activate module.\n";
                        }
                    });
                } catch (\Throwable $e) {
                    echo "Impersonation Error: " . $e->getMessage() . "\n";
                }
            } else {
                echo "Company ahamath not found\n";
            }
        } else {
            echo "SaaS table does not exist\n";
        }
    }
}
