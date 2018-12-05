<?php
$debugAllowed = array(
	'127.0.0.1',
	'::1',
);

// DEBUG configuration
if (isset($_SERVER['REMOTE_ADDR']) AND in_array($_SERVER['REMOTE_ADDR'], $debugAllowed)) {
	ini_set("display_errors", "on");  // Debug setings
	error_reporting(E_ALL);           // Debug setings
	define('MOD_REWRITE', true);      // Mod rewrite (ex. task=page&action=login -> page/login )
	define('setErrorLog', true);      // DB show error

} else {
	ini_set("display_errors", "off"); // Debug setings
	error_reporting(E_ALL);           // Debug setings
	define('MOD_REWRITE', true);      // Mod rewrite (ex. task=page&action=login -> page/login )
	define('setErrorLog', false);     // DB show error

}

// Application configuration
define('APP_DIR', dirname(__FILE__).'/../app/');
define('CODING_STYLE', true);    // Check PSR-2: Coding Style

// Website configuration
define('VERSION', "Dframe");     // Version aplication
define('SALT', "MYSALT123");   // SALT default: MYSALT123

if (isset($_SERVER['REMOTE_ADDR']) AND ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' OR $_SERVER['REMOTE_ADDR'] == '::1')) {
	define('HTTP_HOST', $_SERVER['HTTP_HOST']);  // Dev
} else {
	define('HTTP_HOST', 'website.url'); // Production
}

// Database configuration
define('DB_HOST', "mysql-nicolas-choquet.alwaysdata.net");      // Database Host (localhost)
define('DB_USER', "143175");                                    // Database Username
define('DB_PASS', "2669NICOLAS2107");                            // Database Password
define('DB_DATABASE', "nicolas-choquet_budgets");                // Databese Name
