<?php

class Helpers
{
    public static function asset(string $path): void
    {
        echo WEB_ROUTE . "/assets/css/$path";
    }

    public static function showProfil(): void
    {
        $userConnect = SessionManager::getData(KEY_USERCONNECT);
        if ($userConnect) {
            echo ($userConnect["prenom"] ?? "") . " " . ($userConnect["nom"] ?? "");
        }
    }

    public static function showUrlProfilPhoto(): void
    {
        $userConnect = SessionManager::getData(KEY_USERCONNECT);
        if ($userConnect) {
            echo $userConnect["photo"] ?? "";
        }
    }

    public static function pathUrl(string $uri = ""): void
    {
        echo WEB_ROUTE . "/$uri";
    }
}
