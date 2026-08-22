<?php

namespace App\Core;

class Debug
{
    public static function dd(mixed $data): void
    {
        echo "<pre style='background: #1e1e2e; color: #a6e3a1; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 13px; z-index: 999999; position: relative;'>";
        var_dump($data);
        echo "</pre>";
        die();
    }

    public static function dump(mixed $data): void
    {
        echo "<pre style='background: #1e1e2e; color: #89b4fa; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 13px;'>";
        var_dump($data);
        echo "</pre>";
    }
}
