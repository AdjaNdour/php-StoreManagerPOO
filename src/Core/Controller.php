<?php

abstract class Controller
{
    public function renderView(string $file, array $data = []): void
    {
        $viewData = $data;
        require_once(PATHBASE . "/app/views/$file.html.php");
    }

    public function redirectToRoute(string $uri): void
    {
        header("Location:" . WEB_ROUTE ."/". $uri);
        exit;
    }

    public function renderViewLayout(string $folder, string $layout, array $data = []): void
    {
        $viewData = $data;
        ob_start();
        require_once(PATHBASE . "/src/Views/$folder/index.php");

        $contentView = ob_get_clean();

        require_once(PATHBASE . "/src/Views/layout/$layout.php");
    }
}
