<?php

namespace App\Core;

use App\Model\Entity\Utilisateur;

if (!defined('KEY_USERCONNECT')) {
    define('KEY_USERCONNECT', 'userConnect');
}

class SessionManager
{
    public static function sessionStart(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function getData(string $key): mixed
    {
        self::sessionStart();
        return $_SESSION[$key] ?? null;
    }

    public static function saveData(string $key, mixed $value): void
    {
        self::sessionStart();
        $_SESSION[$key] = $value;
    }

    public static function isConnect(): bool
    {
        self::sessionStart();
        return isset($_SESSION[KEY_USERCONNECT]);
    }

    public static function isRole(string $role): bool
    {
        if (!self::isConnect()) {
            return false;
        }
        $user = self::getData(KEY_USERCONNECT);
        if ($user instanceof Utilisateur) {
            return strtolower($user->getRole()?->getNom() ?? '') === strtolower($role);
        }
        if (is_array($user) && isset($user['role']['nom'])) {
            return strtolower($user['role']['nom']) === strtolower($role);
        }
        return false;
    }

    public static function removeData(string $key): void
    {
        self::sessionStart();
        unset($_SESSION[$key]);
    }

    public static function destroySession(): void
    {
        self::sessionStart();
        self::removeData(KEY_USERCONNECT);
    }
}
