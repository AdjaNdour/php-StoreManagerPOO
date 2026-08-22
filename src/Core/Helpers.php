<?php

namespace App\Core;

use App\Model\Entity\Utilisateur;

class Helpers
{
    public static function asset(string $path): void
    {
        echo WEB_ROUTE . "/assets/css/" . ltrim($path, '/');
    }

    public static function showProfil(): void
    {
        $userConnect = SessionManager::getData(KEY_USERCONNECT);
        if ($userConnect instanceof Utilisateur) {
            echo $userConnect->getNomComplet();
        } elseif (is_array($userConnect)) {
            echo ($userConnect["prenom"] ?? "") . " " . ($userConnect["nom"] ?? "");
        } else {
            echo "Utilisateur";
        }
    }

    public static function showRole(): void
    {
        $userConnect = SessionManager::getData(KEY_USERCONNECT);
        if ($userConnect instanceof Utilisateur) {
            echo strtoupper($userConnect->getRole()?->getNom() ?? 'UTILISATEUR');
        } elseif (is_array($userConnect) && isset($userConnect["role"])) {
            echo strtoupper(is_array($userConnect["role"]) ? ($userConnect["role"]["nom"] ?? 'UTILISATEUR') : (string)$userConnect["role"]);
        } else {
            echo "GESTIONNAIRE";
        }
    }

    public static function showUrlProfilPhoto(): void
    {
        $userConnect = SessionManager::getData(KEY_USERCONNECT);
        if (is_array($userConnect)) {
            echo $userConnect["photo"] ?? "";
        }
    }

    public static function pathUrl(string $uri = ""): void
    {
        $cleanUri = ltrim($uri, '/');
        echo WEB_ROUTE . ($cleanUri !== '' ? '/' . $cleanUri : '');
    }
}
