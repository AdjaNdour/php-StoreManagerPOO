<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->routes = [
            '/' => [
                'controller' => 'DashboardController',
                'className' => 'App\\Controller\\DashboardController',
                'action' => 'index',
            ],
            '/dashboard' => [
                'controller' => 'DashboardController',
                'className' => 'App\\Controller\\DashboardController',
                'action' => 'index',
            ],
            '/dashboard/quick-supply' => [
                'controller' => 'DashboardController',
                'className' => 'App\\Controller\\DashboardController',
                'action' => 'quickSupply',
            ],
            '/dashboard/quickSupply' => [
                'controller' => 'DashboardController',
                'className' => 'App\\Controller\\DashboardController',
                'action' => 'quickSupply',
            ],
            '/pos' => [
                'controller' => 'POSController',
                'className' => 'App\\Controller\\POSController',
                'action' => 'getAllVente',
            ],
            '/ventes' => [
                'controller' => 'POSController',
                'className' => 'App\\Controller\\POSController',
                'action' => 'getAllVente',
            ],
            '/validerVente' => [
                'controller' => 'POSController',
                'className' => 'App\\Controller\\POSController',
                'action' => 'validerVente',
            ],
            '/pos/valider' => [
                'controller' => 'POSController',
                'className' => 'App\\Controller\\POSController',
                'action' => 'validerVente',
            ],
            '/ajouterAuPanier' => [
                'controller' => 'POSController',
                'className' => 'App\\Controller\\POSController',
                'action' => 'ajouterAuPanier',
            ],
            '/pos/panier/add' => [
                'controller' => 'POSController',
                'className' => 'App\\Controller\\POSController',
                'action' => 'ajouterAuPanier',
            ],
            '/supprimerDuPanier' => [
                'controller' => 'POSController',
                'className' => 'App\\Controller\\POSController',
                'action' => 'supprimerDuPanier',
            ],
            '/pos/panier/remove' => [
                'controller' => 'POSController',
                'className' => 'App\\Controller\\POSController',
                'action' => 'supprimerDuPanier',
            ],
            '/dettes' => [
                'controller' => 'DetteController',
                'className' => 'App\\Controller\\DetteController',
                'action' => 'getAllDettes',
            ],
            '/dettes/rembourser' => [
                'controller' => 'DetteController',
                'className' => 'App\\Controller\\DetteController',
                'action' => 'enregistrerRemboursementDette',
            ],
            '/rembourserDette' => [
                'controller' => 'DetteController',
                'className' => 'App\\Controller\\DetteController',
                'action' => 'enregistrerRemboursementDette',
            ],
            '/enregistrerPaiement' => [
                'controller' => 'DetteController',
                'className' => 'App\\Controller\\DetteController',
                'action' => 'enregistrerRemboursementDette',
            ],
            '/dettes/paiement' => [
                'controller' => 'DetteController',
                'className' => 'App\\Controller\\DetteController',
                'action' => 'enregistrerRemboursementDette',
            ],
            '/paiements/save' => [
                'controller' => 'DetteController',
                'className' => 'App\\Controller\\DetteController',
                'action' => 'enregistrerRemboursementDette',
            ],
            '/appros' => [
                'controller' => 'SupplyController',
                'className' => 'App\\Controller\\SupplyController',
                'action' => 'index',
            ],
            '/appro' => [
                'controller' => 'SupplyController',
                'className' => 'App\\Controller\\SupplyController',
                'action' => 'index',
            ],
            '/approvisionnements' => [
                'controller' => 'SupplyController',
                'className' => 'App\\Controller\\SupplyController',
                'action' => 'index',
            ],
            '/appros/save' => [
                'controller' => 'SupplyController',
                'className' => 'App\\Controller\\SupplyController',
                'action' => 'saveAppro',
            ],
            '/appro/save' => [
                'controller' => 'SupplyController',
                'className' => 'App\\Controller\\SupplyController',
                'action' => 'saveAppro',
            ],
            '/appros/receive' => [
                'controller' => 'SupplyController',
                'className' => 'App\\Controller\\SupplyController',
                'action' => 'receiveAppro',
            ],
            '/appro/receive' => [
                'controller' => 'SupplyController',
                'className' => 'App\\Controller\\SupplyController',
                'action' => 'receiveAppro',
            ],
            '/receiveAppro' => [
                'controller' => 'SupplyController',
                'className' => 'App\\Controller\\SupplyController',
                'action' => 'receiveAppro',
            ],
            '/receptionnerAppro' => [
                'controller' => 'SupplyController',
                'className' => 'App\\Controller\\SupplyController',
                'action' => 'receiveAppro',
            ],
            '/receptionnerBL' => [
                'controller' => 'SupplyController',
                'className' => 'App\\Controller\\SupplyController',
                'action' => 'receiveAppro',
            ],
            '/validerReception' => [
                'controller' => 'SupplyController',
                'className' => 'App\\Controller\\SupplyController',
                'action' => 'receiveAppro',
            ],
            '/produits' => [
                'controller' => 'ProduitController',
                'className' => 'App\\Controller\\ProduitController',
                'action' => 'index',
            ],
            '/catalog' => [
                'controller' => 'ProduitController',
                'className' => 'App\\Controller\\ProduitController',
                'action' => 'index',
            ],
            '/tiers' => [
                'controller' => 'ProduitController',
                'className' => 'App\\Controller\\ProduitController',
                'action' => 'index',
            ],
            '/produits/add' => [
                'controller' => 'ProduitController',
                'className' => 'App\\Controller\\ProduitController',
                'action' => 'addProduct',
            ],
            '/clients/add' => [
                'controller' => 'ProduitController',
                'className' => 'App\\Controller\\ProduitController',
                'action' => 'addClient',
            ],
            '/fournisseurs/add' => [
                'controller' => 'ProduitController',
                'className' => 'App\\Controller\\ProduitController',
                'action' => 'addSupplier',
            ],
            '/login' => [
                'controller' => 'AuthController',
                'className' => 'App\\Controller\\AuthController',
                'action' => 'login',
            ],
            '/logout' => [
                'controller' => 'AuthController',
                'className' => 'App\\Controller\\AuthController',
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

        // Authenticated check
        if ($uri !== '/login' && !SessionManager::isConnect()) {
            Controller::redirectToRoute("login");
            return;
        }

        $controllerFile = $this->routes[$uri]['controller'];
        $className = $this->routes[$uri]['className'] ?? "App\\Controller\\$controllerFile";
        $action = $this->routes[$uri]['action'];

        if (class_exists($className)) {
            if (method_exists($className, $action)) {
                $className::$action();
                return;
            }
        }

        $possiblePaths = [
            dirname(__DIR__) . "/Controller/$controllerFile.php",
            dirname(__DIR__) . "/Controllers/$controllerFile.php",
        ];

        foreach ($possiblePaths as $filePath) {
            if (file_exists($filePath)) {
                require_once $filePath;

                if (class_exists($className)) {
                    if (method_exists($className, $action)) {
                        $className::$action();
                        return;
                    }
                }
                if (function_exists($action)) {
                    $action();
                    return;
                }
            }
        }

        http_response_code(404);
        echo "Not found";
    }
}
