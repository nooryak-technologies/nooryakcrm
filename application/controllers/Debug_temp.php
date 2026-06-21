<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Debug_temp extends CI_Controller {
    public function index() {
        if ($this->db->table_exists(db_prefix() . 'perfex_saas_companies')) {
            $this->load->model('perfex_saas/perfex_saas_model');
            $companies = $this->perfex_saas_model->companies();
            $email = 'kkumaira073@gmail.com';
            echo "Searching for $email\n";
            foreach ($companies as $company) {
                if ($company->slug !== 'ahamath') continue;
                echo "Company: " . $company->slug . "\n";
                $dsn = $company->dsn;
                $db_prefix = perfex_saas_tenant_db_prefix($company->slug);
                $table = $db_prefix . 'staff';
                
                $query = "SELECT staffid FROM `$table` WHERE email = :email LIMIT 1";
                $params = [':email' => $email];
                
                try {
                    $result = perfex_saas_raw_query($query, $dsn, true, false, null, false, false, $params);
                    echo "Result class/type: " . gettype($result) . "\n";
                    echo "Result value: " . print_r($result, true) . "\n";
                    if (!empty($result) && reset($result) !== false) {
                        echo "FOUND tenant!\n";
                    } else {
                        echo "NOT FOUND tenant!\n";
                    }
                } catch (\Throwable $e) {
                    echo " - Error: " . $e->getMessage() . "\n";
                }
            }
        }
    }
}
