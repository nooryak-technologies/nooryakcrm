<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Temp_debug extends MX_Controller
{
    public function index()
    {
        $CI =& get_instance();
        $CI->load->library('encryption');
        mysqli_report(MYSQLI_REPORT_OFF);
        
        $companies = $this->db->get(db_prefix() . 'perfex_saas_companies')->result_array();
        echo "Found " . count($companies) . " companies:\n";
        
        foreach ($companies as $company) {
            echo "Company: " . $company['slug'] . " (ID: " . $company['id'] . ")\n";
            $dsn_encrypted = $company['dsn'];
            if (empty($dsn_encrypted)) {
                echo "  Uses master database (nooryak_crm) with default prefix.\n";
                continue;
            }
            
            $dsn_decrypted = $CI->encryption->decrypt($dsn_encrypted);
            echo "  Decrypted DSN: " . $dsn_decrypted . "\n";
            
            // Parse custom DSN format: mysqli:host=localhost;dbname=bazaarwa_ps_expertwer;user=bazaarwa_ps_expertwer;password=HAC981KiawM364z2;
            preg_match('/host=([^;]+)/', $dsn_decrypted, $host_match);
            preg_match('/dbname=([^;]+)/', $dsn_decrypted, $db_match);
            preg_match('/user=([^;]+)/', $dsn_decrypted, $user_match);
            preg_match('/password=([^;]+)/', $dsn_decrypted, $pass_match);
            
            $host = $host_match[1] ?? 'localhost';
            $db = $db_match[1] ?? '';
            $user = $user_match[1] ?? 'root';
            $pass = $pass_match[1] ?? '';
            
            if (empty($db)) {
                echo "  Could not parse dbname from DSN.\n";
                continue;
            }
            
            echo "  Connecting to Host: $host, DB: $db, User: $user\n";
            $conn = @mysqli_connect($host, $user, $pass, $db);
            if ($conn) {
                echo "  Connected successfully!\n";
                
                // Query statuses
                $q_status = mysqli_query($conn, "SELECT id, name, isdefault FROM tblleads_status");
                if ($q_status) {
                    echo "    Statuses:\n";
                    while ($row = mysqli_fetch_assoc($q_status)) {
                        echo "      - ID: " . $row['id'] . ", Name: " . $row['name'] . ", IsDefault: " . $row['isdefault'] . "\n";
                    }
                }
                
                // Query leads count by status
                $q_leads = mysqli_query($conn, "SELECT status, COUNT(*) as cnt FROM tblleads GROUP BY status");
                if ($q_leads) {
                    echo "    Leads by status:\n";
                    while ($row = mysqli_fetch_assoc($q_leads)) {
                        echo "      - Status ID: " . $row['status'] . ", Count: " . $row['cnt'] . "\n";
                    }
                }
                
                // Query all leads
                $q_all = mysqli_query($conn, "SELECT id, name, status, junk FROM tblleads");
                if ($q_all) {
                    echo "    Leads details:\n";
                    while ($row = mysqli_fetch_assoc($q_all)) {
                        echo "      - ID: " . $row['id'] . ", Name: " . $row['name'] . ", Status: " . $row['status'] . ", Junk: " . $row['junk'] . "\n";
                    }
                }
                
                mysqli_close($conn);
            } else {
                echo "  Failed to connect: " . mysqli_connect_error() . "\n";
            }
            echo "\n";
        }
    }
}
