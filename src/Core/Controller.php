<?php

namespace App\Core;

abstract class Controller
{
    public static function renderView(string $folder, array $data = []): void
    {
        $viewData = $data;
        extract($data);
        require(PATHBASE . "/src/Views/$folder/index.php");
    }

    public static function redirectToRoute(string $uri): void
    {
        $target = WEB_ROUTE . ($uri !== '' ? '/' . ltrim($uri, '/') : '/');
        header("Location: " . $target);
        exit;
    }

    public static function renderViewLayout(string $folder, string $layout, array $data = []): void
    {
        $viewData = $data;
        extract($data);
        ob_start();
        require(PATHBASE . "/src/Views/$folder/index.php");
        $contentView = ob_get_clean();
        require(PATHBASE . "/src/Views/layout/$layout.php");
    }
}
