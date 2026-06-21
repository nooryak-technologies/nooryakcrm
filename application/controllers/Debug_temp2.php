<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Debug_temp2 extends CI_Controller {
    public function index() {
        if ($this->db->table_exists(db_prefix() . 'perfex_saas_companies')) {
            $this->load->model('perfex_saas/perfex_saas_model');
            $companies = $this->perfex_saas_model->companies();
            $email = 'kkumaira073@gmail.com';
            $password = '12345678';
            foreach ($companies as $company) {
                if ($company->slug !== 'ahamath') continue;
                $dsn = $company->dsn;
                $db_prefix = perfex_saas_tenant_db_prefix($company->slug);
                $table = $db_prefix . 'staff';
                
                $query = "SELECT password FROM `$table` WHERE email = :email LIMIT 1";
                $params = [':email' => $email];
                
                try {
                    $result = perfex_saas_raw_query($query, $dsn, true, false, null, false, false, $params);
                    if (!empty($result) && reset($result) !== false) {
                        $hash = reset($result)->password;
                        echo "Hash: " . $hash . "\n";
                        if (password_verify($password, $hash)) {
                            echo "Password VERIFIED successfully!\n";
                        } else {
                            echo "Password VERIFICATION FAILED!\n";
                        }
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
