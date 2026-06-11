<?php
/**
 * Quick Update Script for Pricing Packages
 * 
 * Place this file in your CRM root and access it via:
 * http://your-domain.com/update_packages.php?key=UNIQUE_KEY_HERE
 * 
 * Or run from command line:
 * php -f c:\xampp\htdocs\crm\update_packages.php
 */

// Security key - change this to a unique value
define('UPDATE_KEY', md5('nooryak-crm-2026'));

// Check if this is CLI or web request
$is_cli = php_sapi_name() === 'cli';
$provided_key = isset($_GET['key']) ? $_GET['key'] : '';

if (!$is_cli && md5($provided_key) !== UPDATE_KEY) {
    die('Access denied. Please provide correct update key.');
}

// Bootstrap the CodeIgniter application
define('BASEPATH', __DIR__ . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR);
require_once __DIR__ . DIRECTORY_SEPARATOR . 'index.php';

// Now we have access to the CI instance
$CI = &get_instance();
$CI->load->database();
$CI->load->model('perfex_saas_model');

// Execute the update
$success = updatePricingPackages($CI);

if ($is_cli) {
    echo $success ? "✓ Pricing packages updated successfully!\n" : "✗ Error updating pricing packages.\n";
} else {
    echo '<html><body style="font-family: Arial; padding: 20px;">';
    echo $success ? '<h2 style="color: green;">✓ Pricing Packages Updated Successfully!</h2>' : '<h2 style="color: red;">✗ Error Updating Pricing Packages</h2>';
    echo '<p><a href="javascript:history.back()">Go Back</a></p>';
    echo '</body></html>';
}

/**
 * Update or create pricing packages
 */
function updatePricingPackages($CI)
{
    try {
        // Delete old packages (keep demo package)
        $CI->db->where('slug !=', 'demo-package');
        $CI->db->delete(db_prefix() . 'perfex_saas_packages');

        $packages = [
            [
                'name'         => 'Free Trial',
                'slug'         => 'free-trial',
                'price'        => 0.00,
                'description'  => '<p>7-Day Access for 2 Users</p><ul><li>Lead Management</li><li>Sales Management</li><li>Project Management</li><li>Task Management</li><li>Calendar with Reminders</li></ul>',
                'is_default'   => 0,
                'trial_period' => 7,
                'priority'     => 5,
                'staff_limit'  => 2,
            ],
            [
                'name'         => 'Growth',
                'slug'         => 'growth',
                'price'        => 600.00,
                'description'  => '<p>Total ₹3000 / Month Billed Quarterly</p><ul><li>Lead Management</li><li>Sales & Invoice Management</li><li>Mobile App</li><li>Support Tickets</li><li>Email Integration</li><li>SMS & WhatsApp Integration</li></ul>',
                'is_default'   => 0,
                'trial_period' => 0,
                'priority'     => 3,
                'recurring'    => 3,
            ],
            [
                'name'         => 'Professional',
                'slug'         => 'professional',
                'price'        => 700.00,
                'description'  => '<p>Total ₹3500 / Month Billed Quarterly</p><p><strong>GROWTH Features +</strong></p><ul><li>Purchase Management</li><li>Inventory</li><li>Task Management</li><li>Payment Gateway Integration</li><li>Route Tracking</li><li>Multi-currency support</li></ul>',
                'is_default'   => 1,
                'trial_period' => 0,
                'priority'     => 2,
                'recurring'    => 3,
            ],
            [
                'name'         => 'Enterprise',
                'slug'         => 'enterprise',
                'price'        => 900.00,
                'description'  => '<p>Total ₹4500 / Month Billed Quarterly</p><p><strong>PROFESSIONAL Features +</strong></p><ul><li>HR Record</li><li>Project Management</li><li>Zoom Meeting Integration</li><li>Voice Call Integration</li></ul>',
                'is_default'   => 0,
                'trial_period' => 0,
                'priority'     => 1,
                'recurring'    => 3,
            ],
        ];

        foreach ($packages as $package) {
            $metadata = [
                'invoice'      => [
                    'recurring'              => isset($package['recurring']) ? $package['recurring'] : 1,
                    'repeat_every_custom'    => isset($package['recurring']) ? $package['recurring'] : 1,
                    'repeat_type_custom'     => 'month',
                    'allowed_payment_modes'  => [1],
                    'sale_agent'             => '',
                ],
                'client_theme'             => 'single',
                'enable_subdomain'         => 1,
                'enable_custom_domain'     => ($package['slug'] !== 'free-trial') ? 1 : 0,
                'autoapprove_custom_domain' => ($package['slug'] === 'professional' || $package['slug'] === 'enterprise') ? 1 : 0,
                'show_modules_list'        => 'yes',
                'show_limits_on_package'   => 'yes_3',
                'disabled_default_modules' => [],
                'max_instance_limit'       => ($package['slug'] === 'enterprise') ? 5 : 1,
                'limitations'              => [
                    'staff'     => isset($package['staff_limit']) ? $package['staff_limit'] : -1,
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
                    'size'       => ($package['slug'] === 'free-trial') ? 1 : (($package['slug'] === 'growth') ? 10 : (($package['slug'] === 'professional') ? 50 : 200)),
                    'unit'       => 'GB',
                    'unit_price' => '',
                ],
                'allow_customization'      => ($package['slug'] === 'free-trial') ? 'no' : 'yes',
                'disable_module_marketplace' => ($package['slug'] === 'free-trial') ? 'yes' : 'no',
                'disable_service_marketplace' => ($package['slug'] === 'free-trial') ? 'yes' : 'no',
                'assigned_clients'         => [],
                'priority'                 => $package['priority'],
                'is_liftetime_deal'        => '',
            ];

            $insert_data = [
                'name'         => $package['name'],
                'slug'         => $package['slug'],
                'price'        => $package['price'],
                'description'  => $package['description'],
                'bill_interval' => 'month',
                'is_default'   => $package['is_default'] ? 1 : 0,
                'is_private'   => 0,
                'status'       => 1,
                'modules'      => json_encode([]),
                'metadata'     => json_encode($metadata),
                'trial_period' => $package['trial_period'],
            ];

            $CI->db->insert(db_prefix() . 'perfex_saas_packages', $insert_data);
        }

        return true;
    } catch (Exception $e) {
        error_log('Error updating packages: ' . $e->getMessage());
        return false;
    }
}
