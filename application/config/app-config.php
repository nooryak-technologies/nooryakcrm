<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Load environment variables from .env file if it exists
if (file_exists(FCPATH . '.env')) {
    $lines = file(FCPATH . '.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $name = trim($parts[0]);
            $value = trim($parts[1]);
            // Remove quotes if present
            $value = preg_replace('/^["\'](.*)["\']$/', '$1', $value);
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

/*
* --------------------------------------------------------------------------
* Base Site URL
* --------------------------------------------------------------------------
*
* URL to your CodeIgniter root. Typically this will be your base URL,
* WITH a trailing slash:
*
*   http://example.com/
*
* If this is not set then CodeIgniter will try guess the protocol, domain
* and path to your installation. However, you should always configure this
* explicitly and never rely on auto-guessing, especially in production
* environments.
*
*/
$is_https = false;
if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === 1 || $_SERVER['HTTPS'] === '1')) {
    $is_https = true;
} elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $is_https = true;
} elseif (isset($_SERVER['HTTP_FRONT_END_HTTPS']) && $_SERVER['HTTP_FRONT_END_HTTPS'] === 'on') {
    $is_https = true;
}

if (isset($_SERVER['HTTP_HOST'])) {
    $base_url = ($is_https ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
} else {
    $base_url = 'http://localhost/crm/';
}
define('APP_BASE_URL_DEFAULT', $base_url);
define('APP_COOKIE_SECURE', $is_https);

/*
* --------------------------------------------------------------------------
* Encryption Key
* IMPORTANT: Do not change this ever!
* --------------------------------------------------------------------------
*
* If you use the Encryption class, you must set an encryption key.
* See the user guide for more info.
*
* http://codeigniter.com/user_guide/libraries/encryption.html
*
* Auto added on install
*/
define('APP_ENC_KEY', '67b159ba40804810f60d7ce711e39f89');

/**
 * Database Credentials
 * The hostname of your database server
 */
define('APP_DB_HOSTNAME_DEFAULT', getenv('DB_HOSTNAME') ?: 'localhost');

/**
 * The username used to connect to the database
 */
define('APP_DB_USERNAME_DEFAULT', getenv('DB_USERNAME') ?: 'root');

/**
 * The password used to connect to the database
 */
define('APP_DB_PASSWORD_DEFAULT', getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '');

/**
 * The name of the database you want to connect to
 */
define('APP_DB_NAME_DEFAULT', getenv('DB_NAME') ?: 'nooryak_crm');

/**
 * @since  2.3.0
 * Database charset
 */
define('APP_DB_CHARSET', 'utf8mb4');

/**
 * @since  2.3.0
 * Database collatio
 */
define('APP_DB_COLLATION', 'utf8mb4_unicode_ci');

/**
 *
 * Session handler driver
 * By default the database driver will be used.
 *
 * For files session use this config:
 * define('SESS_DRIVER', 'files');
 * define('SESS_SAVE_PATH', NULL);
 * In case you are having problem with the SESS_SAVE_PATH consult with your hosting provider to set "session.save_path" value to php.ini
 *
 */
define('SESS_DRIVER', 'database');
define('SESS_SAVE_PATH', 'sessions');
define('APP_SESSION_COOKIE_SAME_SITE_DEFAULT', 'Lax');

/**
 * Enables CSRF Protection
 */
define('APP_CSRF_PROTECTION', true);//perfex-saas:start:app-config.php
//dont remove/change above line
require_once(FCPATH.'modules/perfex_saas/config/app-config.php');
//dont remove/change below line
//perfex-saas:end:app-config.php