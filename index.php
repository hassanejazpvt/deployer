<?php

use Contrive\Deployer\Libs\Request;

require 'vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', 'off');

define('APP_URL', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']);
define('BASE_PATH', dirname(__FILE__));
define('VIEWS_PATH', BASE_PATH.'/app/Views');

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && $error['type'] === E_ERROR) {
        $e = new Exception($error['message']);
        http_response_code(500);
        include VIEWS_PATH.'/errors/500.php';
    }
});

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    if (! empty($_GET['_c'])) {
        $c = new ('\\Contrive\\Deployer\\Controllers\\'.$_GET['_c'])();
        if (! empty($_GET['_m'])) {
            call_user_func_array([$c, $_GET['_m']], [new Request()]);
        }
    }
    include 'app/Views/layouts/app.php';
} catch (\Exception $e) {
    http_response_code(500);
    include VIEWS_PATH.'/errors/500.php';
}
