<?php

namespace App\Controller;

use Adja\Core\Controller;
use Adja\Core\SessionManager;
use Adja\Core\Request;
use Adja\Core\Validator;
use App\Service\UserService;

class AuthController
{
    public static function login(): void
    {
        $keyUserConnect = defined('KEY_USERCONNECT') ? KEY_USERCONNECT : 'userConnect';
        $baseUrl = defined('WEB_ROUTE') ? WEB_ROUTE : '';
        $viewsPath = defined('PATHBASE') ? PATHBASE . '/src/Views' : 'src/Views';

        if (SessionManager::hasKey($keyUserConnect)) {
            Controller::redirectToRoute("dashboard", $baseUrl);
            return;
        }

        $errors = [];
        $email = '';

        if (Request::isPost()) {
            $email = trim((string)(Request::post('email') ?? Request::post('login') ?? ''));
            $password = trim((string)Request::post('password', ''));

            Validator::required($email, 'email', $errors, "L'adresse email est obligatoire.");
            Validator::isEmail($email, 'email', $errors, "Veuillez entrer une adresse email valide.");
            Validator::required($password, 'password', $errors, "Le mot de passe est obligatoire.");

            if (!Validator::hasErrors($errors)) {
                $user = UserService::getByEmail($email);
                if ($user && $user->verifyPassword($password)) {
                    SessionManager::setData($keyUserConnect, $user);
                    Controller::redirectToRoute("dashboard", $baseUrl);
                    return;
                } else {
                    $errors['auth'] = "Identifiants invalides (email ou mot de passe incorrect).";
                }
            }
        }

        Controller::renderView("auth/login", [
            'errors' => $errors,
            'error' => $errors['auth'] ?? reset($errors) ?: null,
            'email' => $email
        ], $viewsPath);
    }

    public static function logout(): void
    {
        $baseUrl = defined('WEB_ROUTE') ? WEB_ROUTE : '';
        SessionManager::destroySession();
        Controller::redirectToRoute("login", $baseUrl);
    }
}
