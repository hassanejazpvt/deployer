<?php

use Contrive\Deployer\Controllers\ServerController;
use Contrive\Deployer\Libs\Request;

require 'vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 'on');

define('APP_URL', 'http://localhost:82');
define('BASE_PATH', dirname(__FILE__));

try {
    if (! empty($_GET['_c'])) {
        $c = new ('\\Contrive\\Deployer\\Controllers\\'.$_GET['_c'])();
        if (! empty($_GET['_m'])) {
            call_user_func_array([$c, $_GET['_m']], [new Request()]);
        }
    }
    include 'src/Views/layouts/app.php';
} catch (\Exception $e) {
    dd($e);
}
