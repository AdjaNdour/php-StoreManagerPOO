<?php
define('PATHBASE', dirname(__DIR__));
require_once PATHBASE . "/src/Core/SessionManager.php";
SessionManager::sessionStart();
require_once PATHBASE . "/src/Core/Helpers.php";
require_once PATHBASE . "/src/Core/Debug.php";
require_once PATHBASE . "/src/Core/Database.php";
require_once PATHBASE . "/src/Core/Router.php";

$router = new Router();
$router->run();
