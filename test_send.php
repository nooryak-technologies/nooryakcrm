<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Bootstrap CodeIgniter
define('WP_USE_THEMES', false);
define('BASEPATH', '1');
// Let's load CI instance by fetching from the index.php environment or using curl to query the local send_otp endpoint directly.
