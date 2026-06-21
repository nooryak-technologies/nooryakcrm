<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Debug_temp3 extends CI_Controller {
    public function index() {
        if ($this->db->table_exists(db_prefix() . 'perfex_saas_companies')) {
            $this->load->model('perfex_saas/perfex_saas_model');
            $companies = $this->perfex_saas_model->companies();
            $email = 'kkumaira073@gmail.com';
            foreach ($companies as $company) {
                if ($company->slug !== 'ahamath') continue;
                $dsn = $company->dsn;
                $db_prefix = perfex_saas_tenant_db_prefix($company->slug);
                $table = $db_prefix . 'staff';
                
                $query = "SELECT staffid, email, active, admin FROM `$table` WHERE email = :email LIMIT 1";
                $params = [':email' => $email];
                
                try {
                    $result = perfex_saas_raw_query($query, $dsn, true, false, null, false, false, $params);
                    if (!empty($result) && reset($result) !== false) {
                        $row = reset($result);
                        echo "Staff ID: " . $row->staffid . "\n";
                        echo "Email: " . $row->email . "\n";
                        echo "Active: " . $row->active . "\n";
                        echo "Admin: " . $row->admin . "\n";
                    } else {
                        echo "User not found in tenant\n";
                    }
                } catch (\Throwable $e) {
                    echo " - Error: " . $e->getMessage() . "\n";
                }
            }
        }
    }
}
