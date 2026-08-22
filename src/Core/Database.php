<?php

namespace App\Core;

use PDO;
use Exception;
use PDOException;
use stdClass;

class Database
{
    private static ?PDO $pdo = null;

    public static function getInstance(): PDO
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO("pgsql:host=localhost;port=5432;dbname=storemanagerpro", "postgres", "kiki", [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (Exception $e) {
                $dbPath = dirname(__DIR__, 2) . "/erp.db";
                self::$pdo = new PDO("sqlite:" . $dbPath, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            }
        }

        return self::$pdo;
    }

    public static function executeQuery(string $sql, array $params = [], bool $single = false): mixed
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);

        if ($single) {
            return $stmt->fetch();
        }

        return $stmt->fetchAll();
    }

    public static function query(string $sql, bool $single = false): mixed
    {
        $stmt = self::getInstance()->query($sql);

        if ($single) {
            return $stmt->fetch();
        }

        return $stmt->fetchAll();
    }

    public static function executeUpdate(string $sql, array $params = []): int
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public static function getLastId(string $table, string $primaryKey = 'id'): int
    {
        $sql = "SELECT COALESCE(MAX($primaryKey), 0) AS max_id FROM $table";
        $result = self::query($sql, true);
        return (int)($result->max_id ?? 0);
    }
}
