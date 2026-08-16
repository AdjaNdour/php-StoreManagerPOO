<?php

class Debug
{
    public static function dump(mixed $data): void
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }

    public static function dd(mixed $data): void
    {
        self::dump($data);
        die;
    }
}

