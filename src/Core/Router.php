<?php

require_once(PATHBASE . "/src/Core/Controller.php");

class Router
{
    private array $routes = [];

    public function __construct()
    {
        if (!defined("WEB_ROUTE")) {
            $protocole = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            define("WEB_ROUTE", $protocole . '://' . $host);
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

        ];
    }

    public function run(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        if (!isset($this->routes[$uri])) {
            http_response_code(404);
            echo "Page introuvable";
            exit;
        }

        $controllerFile = $this->routes[$uri]['controller'];
        $className = $this->routes[$uri]['className'] ?? $controllerFile;
        $action = $this->routes[$uri]['action'];

        $filePath = dirname(__DIR__) . "/Controller/$controllerFile.php";

        if (file_exists($filePath)) {
            require_once $filePath;

            if (class_exists($className)) {
                $controllerInstance = new $className();
                if (method_exists($controllerInstance, $action)) {
                    $controllerInstance->$action();
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
