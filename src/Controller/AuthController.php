<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\SessionManager;
use App\Core\Request;
use App\Core\Validator;
use App\Service\UserService;

class AuthController
{
    public static function login(): void
    {
        if (SessionManager::isConnect()) {
            Controller::redirectToRoute("dashboard");
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
                $user = UserService::getUserByEmail($email);
                if ($user && $user->verifyPassword($password)) {
                    SessionManager::saveData(KEY_USERCONNECT, $user);
                    Controller::redirectToRoute("dashboard");
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
        ]);
    }

    public static function logout(): void
    {
        SessionManager::destroySession();
        Controller::redirectToRoute("login");
    }
}
