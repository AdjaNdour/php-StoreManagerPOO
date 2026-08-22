<?php

if (!defined('PATHBASE')) {
    define('PATHBASE', dirname(__DIR__));
}

if (!defined('WEB_ROUTE')) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($scriptDir === '/' || $scriptDir === '.') {
        $scriptDir = '';
    }
    define('WEB_ROUTE', $scriptDir);
}

if (file_exists(PATHBASE . '/vendor/autoload.php')) {
    require_once PATHBASE . '/vendor/autoload.php';
}

use App\Core\Router;
use App\Core\SessionManager;

SessionManager::sessionStart();

$router = new Router();
$router->run();
