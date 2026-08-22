<?php

namespace App\Core;

class Validator
{
    public static function required(mixed $value, string $key, array &$errors, string $smsError = "Ce champ est obligatoire"): bool
    {
        if ($value === null || trim((string)$value) === '') {
            $errors[$key] = $smsError;
            return false;
        }
        return true;
    }

    public static function isPositive(mixed $value, string $key, array &$errors, string $smsError = "Ce champ doit être un nombre positif"): bool
    {
        if (!is_numeric($value) || (float)$value < 0) {
            $errors[$key] = $smsError;
            return false;
        }
        return true;
    }

    public static function isGreaterThanZero(mixed $value, string $key, array &$errors, string $smsError = "Ce champ doit être strictement supérieur à zéro"): bool
    {
        if (!is_numeric($value) || (float)$value <= 0) {
            $errors[$key] = $smsError;
            return false;
        }
        return true;
    }

    public static function isEmail(mixed $value, string $key, array &$errors, string $smsError = "Adresse email invalide"): bool
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[$key] = $smsError;
            return false;
        }
        return true;
    }

    public static function minLength(mixed $value, int $min, string $key, array &$errors, string $smsError = "Ce champ est trop court"): bool
    {
        if (strlen(trim((string)$value)) < $min) {
            $errors[$key] = $smsError;
            return false;
        }
        return true;
    }

    public static function unique(mixed $value, string $key, array $datas, array &$errors, bool $required = true, string $smsError = "Cette valeur existe déjà"): void
    {
        if (!$required && empty($value)) {
            return;
        }
        foreach ($datas as $data) {
            if (is_array($data) && isset($data[$key]) && $data[$key] == $value) {
                $errors[$key] = $smsError;
                return;
            } elseif (is_object($data) && isset($data->$key) && $data->$key == $value) {
                $errors[$key] = $smsError;
                return;
            }
        }
    }

    public static function hasErrors(array $errors): bool
    {
        return !empty($errors);
    }
}
