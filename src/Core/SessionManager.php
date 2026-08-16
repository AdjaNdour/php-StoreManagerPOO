<?php

define('KEY_USERCONNECT', 'userConnect');

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
        return $_SESSION[$key] ?? null;
    }

    public static function saveData(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function isConnect(): bool
    {
        return isset($_SESSION[KEY_USERCONNECT]);
    }

    public static function isRole(string $role): bool
    {
        return self::isConnect() && isset($_SESSION[KEY_USERCONNECT]['role']['nom']) && $_SESSION[KEY_USERCONNECT]['role']['nom'] === $role;
    }

    public static function removeData(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroySession(): void
    {
        self::removeData(KEY_USERCONNECT);
    }
}
