<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
@session_start();
// Database config
define('DB_PERSISTENT','true');
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASSWORD','my1234');
define('DB_NAME','dbrpu');
define('PDO_DSN','mysql:host='.DB_HOST.';dbname='.DB_NAME);

// SITE_ROOT
$file = str_replace('\\', '/', __FILE__);
$docRoot = '/localhost/rpu_prod/';
$webRoot = str_replace(array($docRoot,'libs/config.php'),'', $file);
$srvRoot = str_replace('libs/config.php','', $file);

define('WEB_ROOT', $webRoot);
define('SRV_ROOT', $srvRoot);
define('BASE_URI','http://localhost/rpu_prod/');

if (!get_magic_quotes_gpc()) {
    if (isset($_POST)) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = trim(addslashes($value));
        }
    }

    if (isset($_GET)) {
        foreach ($_GET as $key => $value) {
            $_GET[$key] = trim(addslashes($value));
        }
    }
}
require_once 'db_handler.php';
require_once 'rpu_katalog.php';
require_once 'rpu_tools.php';
require_once 'rpu_pembelian.php';
require_once 'rpu_penjualan.php';
require_once 'rpu_user.php';