<?php

namespace App\Core;

use Adja\Core\Controller;
use Adja\Core\SessionManager;

class Router
{
    private array $routes = [];

    public function __construct()
    {
        if (!defined("WEB_ROUTE")) {
            $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
            if ($scriptDir === '/' || $scriptDir === '.') {
                $scriptDir = '';
            }
            define("WEB_ROUTE", $scriptDir);
        }

        $this->routes = [
            '/' => [
                'controller' => 'POSController',
                'className' => 'POSController',
                'action' => 'getAllVente',
            ],
            '/pos' => [
                'controller' => 'POSController',
                'className' => 'POSController',
                'action' => 'getAllVente',
            ],
            '/validerVente' => [
                'controller' => 'POSController',
                'className' => 'POSController',
                'action' => 'validerVente',
            ],
            '/supprimerDuPanier' => [
                'controller' => 'POSController',
                'className' => 'POSController',
                'action' => 'supprimerDuPanier',
            ],
            //---------------------------------------------
            '/dashboard' => [
                'controller' => 'DashboardController',
                'className' => 'DashboardController',
                'action' => 'index',
            ],
            '/dashboard/quickSupply' => [
                'controller' => 'DashboardController',
                'className' => 'DashboardController',
                'action' => 'quickSupply',
            ],
            //---------------------------------------------
            '/dettes' => [
                'controller' => 'DetteController',
                'className' => 'DetteController',
                'action' => 'getAllDettes',
            ],
            '/dettes/rembourser' => [
                'controller' => 'DetteController',
                'className' => 'DetteController',
                'action' => 'enregistrerRemboursementDette',
            ],
            //---------------------------------------------
            '/appros' => [
                'controller' => 'SupplyController',
                'className' => 'SupplyController',
                'action' => 'index',
            ],
            '/appros/receive' => [
                'controller' => 'SupplyController',
                'className' => 'SupplyController',
                'action' => 'receiveAppro',
            ],
            //---------------------------------------------
            '/produits' => [
                'controller' => 'ProduitController',
                'className' => 'ProduitController',
                'action' => 'index',
            ],
            '/produits/add' => [
                'controller' => 'ProduitController',
                'className' => 'ProduitController',
                'action' => 'addProduct',
            ],
            '/clients/add' => [
                'controller' => 'ProduitController',
                'className' => 'ProduitController',
                'action' => 'addClient',
            ],
            '/fournisseurs/add' => [
                'controller' => 'ProduitController',
                'className' => 'ProduitController',
                'action' => 'addSupplier',
            ],
            //---------------------------------------------
            '/login' => [
                'controller' => 'AuthController',
                'className' => 'AuthController',
                'action' => 'login',
            ],
            '/logout' => [
                'controller' => 'AuthController',
                'className' => 'AuthController',
                'action' => 'logout',
            ],
        ];
    }

    public function run(): void
    {
        $rawUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($scriptDir === '/' || $scriptDir === '.') {
            $scriptDir = '';
        }

        $uri = $rawUri;
        if (!empty($scriptDir) && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir));
        }

        if (str_starts_with($uri, '/index.php')) {
            $uri = substr($uri, strlen('/index.php'));
        }

        if ($uri === '' || $uri === false) {
            $uri = '/';
        }

        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        if (!isset($this->routes[$uri])) {
            http_response_code(404);
            echo "Page introuvable : " . htmlspecialchars($uri);
            exit;
        }

        $keyUserConnect = defined('KEY_USERCONNECT') ? KEY_USERCONNECT : 'userConnect';
        $baseUrl = defined('WEB_ROUTE') ? WEB_ROUTE : '';
        if ($uri !== '/login' && !SessionManager::hasKey($keyUserConnect)) {
            Controller::redirectToRoute("login", $baseUrl);
            return;
        }

        $controllerFile = $this->routes[$uri]['controller'];
        $className = $this->routes[$uri]['className'] ?? $controllerFile;
        $action = $this->routes[$uri]['action'];

        $filePath = dirname(__DIR__) . "/Controller/$controllerFile.php";

        if (file_exists($filePath)) {
            require_once $filePath;

            $targetClass = class_exists("App\\Controller\\$className") ? "App\\Controller\\$className" : (class_exists($className) ? $className : null);

            if ($targetClass !== null) {
                $controllerInstance = new $targetClass();
                if (method_exists($controllerInstance, $action)) {
                    $controllerInstance->$action();
                    return;
                }
                if (method_exists($targetClass, $action)) {
                    $targetClass::$action();
                    return;
                }
            }

            if (function_exists($action)) {
                $action();
                return;
            }
        }

        http_response_code(404);
        echo "Not found";
    }
}
