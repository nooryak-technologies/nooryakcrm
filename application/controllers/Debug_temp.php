<?php
defined('BASEPATH') || exit('No direct script access allowed');

class Debug_temp extends CI_Controller {
    public function index() {
        if ($this->db->table_exists(db_prefix() . 'perfex_saas_companies')) {
            $this->load->model('perfex_saas/perfex_saas_model');
            $companies = $this->perfex_saas_model->companies();
            echo "Total companies: " . count($companies) . "\n";
            foreach ($companies as $company) {
                echo "Company: " . $company->slug . " (ID: " . $company->id . ")\n";
                $dsn = $company->dsn;
                $db_prefix = perfex_saas_tenant_db_prefix($company->slug);
                $table = $db_prefix . 'staff';
                
                $query = "SELECT staffid, email, firstname, lastname FROM `$table`";
                try {
                    $result = perfex_saas_raw_query($query, $dsn, true, false, null, false, false);
                    if (!empty($result)) {
                        foreach ($result as $row) {
                            echo " - Staff: " . $row->staffid . " | Email: " . $row->email . " | Name: " . $row->firstname . "\n";
                        }
                    } else {
                        echo " - No staff found\n";
                    }
                } catch (\Throwable $e) {
                    echo " - Error querying: " . $e->getMessage() . "\n";
                }
            }
        } else {
            echo "SaaS table does not exist\n";
        }
    }
}
