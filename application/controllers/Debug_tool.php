<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Debug_tool extends CI_Controller {
    public function index() {
        header('Content-Type: text/plain');
        echo "--- Live Production Database Debugger ---\n";
        $email = 'kkumaira073@gmail.com';

        // 1. Check Master Staff
        $this->db->where('email', $email);
        $master_staff = $this->db->get(db_prefix() . 'staff')->row();
        if ($master_staff) {
            echo "Found in Master tblstaff: ID {$master_staff->staffid}, Name: {$master_staff->firstname} {$master_staff->lastname}\n";
        } else {
            echo "Not found in Master tblstaff.\n";
        }

        // 2. Check Master Contacts (Customers)
        $this->db->where('email', $email);
        $master_contact = $this->db->get(db_prefix() . 'contacts')->row();
        if ($master_contact) {
            echo "Found in Master tblcontacts (Customer): ID {$master_contact->id}, UserID: {$master_contact->userid}, Name: {$master_contact->firstname} {$master_contact->lastname}\n";
        } else {
            echo "Not found in Master tblcontacts.\n";
        }

        // 3. Scan SaaS Companies
        if ($this->db->table_exists(db_prefix() . 'perfex_saas_companies')) {
            $this->load->model('perfex_saas/perfex_saas_model');
            $companies = $this->perfex_saas_model->companies();
            echo "\nTotal SaaS Companies found: " . count($companies) . "\n\n";
            
            $this->load->helper('perfex_saas/perfex_saas');

            foreach ($companies as $company) {
                $dsn = $company->dsn;
                $db_prefix = perfex_saas_tenant_db_prefix($company->slug);
                
                echo "Company: {$company->name} (Slug: {$company->slug})\n";
                echo "  Base URL: " . perfex_saas_tenant_base_url($company) . "\n";
                
                // Query contacts in tenant
                $table_contacts = $db_prefix . 'contacts';
                $query_contacts = "SELECT id, email, firstname, lastname FROM `$table_contacts` WHERE email = :email LIMIT 1";
                
                // Query staff in tenant
                $table_staff = $db_prefix . 'staff';
                $query_staff = "SELECT staffid, email, firstname, lastname FROM `$table_staff` WHERE email = :email LIMIT 1";
                
                $params = [':email' => $email];
                
                // Check if staff table exists
                try {
                    $result_staff = perfex_saas_raw_query($query_staff, $dsn, true, false, null, false, false, $params);
                    if (!empty($result_staff) && reset($result_staff) !== false) {
                        echo "  -> FOUND in Tenant tblstaff!\n";
                        print_r($result_staff);
                    } else {
                        echo "  -> Not in Tenant tblstaff.\n";
                    }
                } catch (\Throwable $e) {
                    echo "  -> Error checking tblstaff: " . $e->getMessage() . "\n";
                }

                try {
                    $result_contacts = perfex_saas_raw_query($query_contacts, $dsn, true, false, null, false, false, $params);
                    if (!empty($result_contacts) && reset($result_contacts) !== false) {
                        echo "  -> FOUND in Tenant tblcontacts (Customer)!\n";
                        print_r($result_contacts);
                    } else {
                        echo "  -> Not in Tenant tblcontacts.\n";
                    }
                } catch (\Throwable $e) {
                    echo "  -> Error checking tblcontacts: " . $e->getMessage() . "\n";
                }
                
                // Print all registered staff in this tenant to see what staff exist
                try {
                    $query_all_staff = "SELECT staffid, email, firstname, lastname FROM `$table_staff` LIMIT 10";
                    $all_staff = perfex_saas_raw_query($query_all_staff, $dsn, true, false, null, false, false, []);
                    if (!empty($all_staff) && reset($all_staff) !== false) {
                        echo "  -> Staff registered in this tenant (up to 10):\n";
                        foreach ($all_staff as $st) {
                            echo "     - ID: {$st->staffid}, Email: {$st->email}, Name: {$st->firstname} {$st->lastname}\n";
                        }
                    } else {
                        echo "  -> No staff registered in this tenant.\n";
                    }
                } catch (\Throwable $e) {
                    echo "  -> Error listing all staff: " . $e->getMessage() . "\n";
                }
                echo "\n";
            }
        } else {
            echo "perfex_saas_companies table does not exist.\n";
        }
    }
}
