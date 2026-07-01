<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$system_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'system';
$application_folder = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'application';

if (realpath($system_path) !== false) {
    $system_path = realpath($system_path) . '/';
}
$system_path = rtrim($system_path, '/') . '/';

define('BASEPATH', str_replace('\\', '/', $system_path));
define('APPPATH', $application_folder . '/');
$view_folder = APPPATH . 'views';
define('VIEWPATH', $view_folder . DIRECTORY_SEPARATOR);
define('EXT', '.php');
define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__FILE__) . '/');

if (file_exists(APPPATH . 'config/' . ENVIRONMENT . '/constants.php')) {
    require(APPPATH . 'config/' . ENVIRONMENT . '/constants.php');
} else {
    require(APPPATH . 'config/constants.php');
}

require(BASEPATH . 'core/Common.php');

if (file_exists(APPPATH . 'vendor/autoload.php')) {
    require_once(APPPATH . 'vendor/autoload.php');
}

require_once(APPPATH . 'config/hooks.php');
require_once(APPPATH . 'hooks/App_Autoloader.php');
(new App_Autoloader)->register();

define('MB_ENABLED', extension_loaded('mbstring'));
define('ICONV_ENABLED', extension_loaded('iconv'));

$GLOBALS['CFG'] = & load_class('Config', 'core');
$GLOBALS['UNI'] = & load_class('Utf8', 'core');
if (file_exists(BASEPATH . 'core/Security.php')) {
    $GLOBALS['SEC'] = & load_class('Security', 'core');
}
load_class('Router', 'core');
load_class('Input', 'core');
load_class('Lang', 'core');

require(BASEPATH . 'core/Controller.php');

function &get_instance()
{
    return CI_Controller::get_instance();
}

$class    = 'CI_Controller';
$instance = new $class();

require_once(APPPATH . 'hooks/InitHook.php');
_app_init_load();

$instance->load->model('invoices_model');
$instance->load->helper('pdf');

// Fetch the most recent invoice from the DB
$instance->db->order_by('id', 'desc');
$invoice = $instance->db->get(db_prefix() . 'invoices')->row();

if (!$invoice) {
    die("No invoice found in the database. Please create one first.\n");
}

$invoice = $instance->invoices_model->get($invoice->id);
echo "Generating PDF for Invoice ID: " . $invoice->id . " (" . $invoice->number . ")...\n";

try {
    $pdf = invoice_pdf($invoice);
    $pdf_content = $pdf->Output('invoice_' . $invoice->id . '.pdf', 'S');
    file_put_contents('invoice_test_' . $invoice->id . '.pdf', $pdf_content);
    echo "✓ PDF successfully generated and saved to invoice_test_" . $invoice->id . ".pdf\n";
} catch (Exception $e) {
    echo "✗ Failed to generate PDF: " . $e->getMessage() . "\n";
}
