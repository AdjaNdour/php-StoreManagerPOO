<?php

if (!defined('PATHBASE')) {
    define('PATHBASE', dirname(__DIR__));
}

if (!defined('KEY_USERCONNECT')) {
    define('KEY_USERCONNECT', 'userConnect');
}

if (file_exists(PATHBASE . '/vendor/autoload.php')) {
    require_once PATHBASE . '/vendor/autoload.php';
}

use App\Core\Router;
use Adja\Core\SessionManager;
use Adja\Core\Database;

Database::init('localhost', 5432, 'storemanagerpro', 'postgres', 'kiki', 'pgsql');
SessionManager::startSession();

$router = new Router();
$router->run();
