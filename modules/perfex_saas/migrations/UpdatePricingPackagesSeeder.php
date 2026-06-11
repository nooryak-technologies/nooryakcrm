<?php
/**
 * Seeder: Update Pricing Packages
 * 
 * This script updates the SaaS pricing packages in the database.
 * It can be run manually or through the admin panel.
 * 
 * Usage: php -r "include 'index.php'; \$this->db->query(file_get_contents('modules/perfex_saas/migrations/update_pricing_packages.sql'));"
 */

defined('BASEPATH') or exit('No direct script access allowed');

class UpdatePricingPackagesSeeder
{
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('perfex_saas_model');
    }

    /**
     * Run the seeder to update packages
     */
    public function run()
    {
        // Delete old packages (keep demo package)
        $this->CI->db->where('slug !=', 'demo-package');
        $this->CI->db->delete(db_prefix() . 'perfex_saas_packages');

        // Insert Free Trial Package
        $this->insertFreeTrialPackage();

        // Insert Growth Package
        $this->insertGrowthPackage();

        // Insert Professional Package
        $this->insertProfessionalPackage();

        // Insert Enterprise Package
        $this->insertEnterprisePackage();

        return true;
    }

    private function insertFreeTrialPackage()
    {
        $data = [
            'name'         => 'Free Trial',
            'description'  => '<p>7-Day Access for 2 Users</p><ul><li>Lead Management</li><li>Sales Management</li><li>Project Management</li><li>Task Management</li><li>Calendar with Reminders</li></ul>',
            'slug'         => 'free-trial',
            'price'        => 0.00,
            'bill_interval' => 'month',
            'is_default'   => 0,
            'is_private'   => 0,
            'status'       => 1,
            'modules'      => json_encode([]),
            'metadata'     => json_encode([
                'invoice'      => [
                    'recurring'              => 1,
                    'repeat_every_custom'    => 1,
                    'repeat_type_custom'     => 'month',
                    'allowed_payment_modes'  => [1],
                    'sale_agent'             => '',
                ],
                'client_theme'             => 'single',
                'enable_subdomain'         => 1,
                'enable_custom_domain'     => 0,
                'autoapprove_custom_domain' => 0,
                'show_modules_list'        => 'yes',
                'show_limits_on_package'   => 'yes_3',
                'disabled_default_modules' => [],
                'max_instance_limit'       => 1,
                'limitations'              => [
                    'staff'     => 2,
                    'clients'   => -1,
                    'contacts'  => -1,
                    'contracts' => -1,
                    'invoices'  => -1,
                    'estimates' => -1,
                    'creditnotes' => -1,
                    'proposals' => -1,
                    'projects'  => -1,
                    'tasks'     => -1,
                    'tickets'   => -1,
                    'leads'     => -1,
                    'items'     => -1,
                ],
                'storage_limit'            => [
                    'size'       => 1,
                    'unit'       => 'GB',
                    'unit_price' => '',
                ],
                'allow_customization'      => 'no',
                'disable_module_marketplace' => 'yes',
                'disable_service_marketplace' => 'yes',
                'assigned_clients'         => [],
                'priority'                 => 5,
                'is_liftetime_deal'        => '',
            ]),
            'trial_period' => 7,
        ];

        $this->CI->db->insert(db_prefix() . 'perfex_saas_packages', $data);
    }

    private function insertGrowthPackage()
    {
        $data = [
            'name'         => 'Growth',
            'description'  => '<p>Total ₹3000 / Month Billed Quarterly</p><ul><li>Lead Management</li><li>Sales & Invoice Management</li><li>Mobile App</li><li>Support Tickets</li><li>Email Integration</li><li>SMS & WhatsApp Integration</li></ul>',
            'slug'         => 'growth',
            'price'        => 600.00,
            'bill_interval' => 'month',
            'is_default'   => 0,
            'is_private'   => 0,
            'status'       => 1,
            'modules'      => json_encode([]),
            'metadata'     => json_encode([
                'invoice'      => [
                    'recurring'              => 3,
                    'repeat_every_custom'    => 3,
                    'repeat_type_custom'     => 'month',
                    'allowed_payment_modes'  => [1],
                    'sale_agent'             => '',
                ],
                'client_theme'             => 'single',
                'enable_subdomain'         => 1,
                'enable_custom_domain'     => 1,
                'autoapprove_custom_domain' => 0,
                'show_modules_list'        => 'yes',
                'show_limits_on_package'   => 'yes_3',
                'disabled_default_modules' => [],
                'max_instance_limit'       => 1,
                'limitations'              => [
                    'staff'     => -1,
                    'clients'   => -1,
                    'contacts'  => -1,
                    'contracts' => -1,
                    'invoices'  => -1,
                    'estimates' => -1,
                    'creditnotes' => -1,
                    'proposals' => -1,
                    'projects'  => -1,
                    'tasks'     => -1,
                    'tickets'   => -1,
                    'leads'     => -1,
                    'items'     => -1,
                ],
                'storage_limit'            => [
                    'size'       => 10,
                    'unit'       => 'GB',
                    'unit_price' => '',
                ],
                'allow_customization'      => 'yes',
                'disable_module_marketplace' => 'no',
                'disable_service_marketplace' => 'no',
                'assigned_clients'         => [],
                'priority'                 => 3,
                'is_liftetime_deal'        => '',
            ]),
            'trial_period' => 0,
        ];

        $this->CI->db->insert(db_prefix() . 'perfex_saas_packages', $data);
    }

    private function insertProfessionalPackage()
    {
        $data = [
            'name'         => 'Professional',
            'description'  => '<p>Total ₹3500 / Month Billed Quarterly</p><p><strong>GROWTH Features +</strong></p><ul><li>Purchase Management</li><li>Inventory</li><li>Task Management</li><li>Payment Gateway Integration</li><li>Route Tracking</li><li>Multi-currency support</li></ul>',
            'slug'         => 'professional',
            'price'        => 700.00,
            'bill_interval' => 'month',
            'is_default'   => 1,
            'is_private'   => 0,
            'status'       => 1,
            'modules'      => json_encode([]),
            'metadata'     => json_encode([
                'invoice'      => [
                    'recurring'              => 3,
                    'repeat_every_custom'    => 3,
                    'repeat_type_custom'     => 'month',
                    'allowed_payment_modes'  => [1],
                    'sale_agent'             => '',
                ],
                'client_theme'             => 'single',
                'enable_subdomain'         => 1,
                'enable_custom_domain'     => 1,
                'autoapprove_custom_domain' => 1,
                'show_modules_list'        => 'yes',
                'show_limits_on_package'   => 'yes_3',
                'disabled_default_modules' => [],
                'max_instance_limit'       => 1,
                'limitations'              => [
                    'staff'     => -1,
                    'clients'   => -1,
                    'contacts'  => -1,
                    'contracts' => -1,
                    'invoices'  => -1,
                    'estimates' => -1,
                    'creditnotes' => -1,
                    'proposals' => -1,
                    'projects'  => -1,
                    'tasks'     => -1,
                    'tickets'   => -1,
                    'leads'     => -1,
                    'items'     => -1,
                ],
                'storage_limit'            => [
                    'size'       => 50,
                    'unit'       => 'GB',
                    'unit_price' => '',
                ],
                'allow_customization'      => 'yes',
                'disable_module_marketplace' => 'no',
                'disable_service_marketplace' => 'no',
                'assigned_clients'         => [],
                'priority'                 => 2,
                'is_liftetime_deal'        => '',
            ]),
            'trial_period' => 0,
        ];

        $this->CI->db->insert(db_prefix() . 'perfex_saas_packages', $data);
    }

    private function insertEnterprisePackage()
    {
        $data = [
            'name'         => 'Enterprise',
            'description'  => '<p>Total ₹4500 / Month Billed Quarterly</p><p><strong>PROFESSIONAL Features +</strong></p><ul><li>HR Record</li><li>Project Management</li><li>Zoom Meeting Integration</li><li>Voice Call Integration</li></ul>',
            'slug'         => 'enterprise',
            'price'        => 900.00,
            'bill_interval' => 'month',
            'is_default'   => 0,
            'is_private'   => 0,
            'status'       => 1,
            'modules'      => json_encode([]),
            'metadata'     => json_encode([
                'invoice'      => [
                    'recurring'              => 3,
                    'repeat_every_custom'    => 3,
                    'repeat_type_custom'     => 'month',
                    'allowed_payment_modes'  => [1],
                    'sale_agent'             => '',
                ],
                'client_theme'             => 'single',
                'enable_subdomain'         => 1,
                'enable_custom_domain'     => 1,
                'autoapprove_custom_domain' => 1,
                'show_modules_list'        => 'yes',
                'show_limits_on_package'   => 'yes_3',
                'disabled_default_modules' => [],
                'max_instance_limit'       => 5,
                'limitations'              => [
                    'staff'     => -1,
                    'clients'   => -1,
                    'contacts'  => -1,
                    'contracts' => -1,
                    'invoices'  => -1,
                    'estimates' => -1,
                    'creditnotes' => -1,
                    'proposals' => -1,
                    'projects'  => -1,
                    'tasks'     => -1,
                    'tickets'   => -1,
                    'leads'     => -1,
                    'items'     => -1,
                ],
                'storage_limit'            => [
                    'size'       => 200,
                    'unit'       => 'GB',
                    'unit_price' => '',
                ],
                'allow_customization'      => 'yes',
                'disable_module_marketplace' => 'no',
                'disable_service_marketplace' => 'no',
                'assigned_clients'         => [],
                'priority'                 => 1,
                'is_liftetime_deal'        => '',
            ]),
            'trial_period' => 0,
        ];

        $this->CI->db->insert(db_prefix() . 'perfex_saas_packages', $data);
    }
}

// Execute seeder if run directly
if (php_sapi_name() === 'cli' || isset($_GET['run_seeder'])) {
    $seeder = new UpdatePricingPackagesSeeder();
    if ($seeder->run()) {
        echo "✓ Pricing packages updated successfully!\n";
    } else {
        echo "✗ Error updating pricing packages.\n";
    }
}
