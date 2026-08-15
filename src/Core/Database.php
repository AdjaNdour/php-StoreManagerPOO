<?php

class Database
{
    private static ?PDO $instanceDB = null;
    private static string $driver = 'sql';

    public static function connexionDB(): PDO
    {
        if (self::$instanceDB === null) {

            try {
                self::$instanceDB = new PDO("pgsql:host=localhost;port=5432;dbname=storemanager", "postgres", "kiki");
                self::$driver = 'pgsql';

            } catch (Exception $e) {

                self::$instanceDB = new PDO("sqlite:" . dirname(__DIR__) . "/erp.db");
                self::$driver = 'sqlite';
            }
            self::$instanceDB->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instanceDB->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        return self::$instanceDB;
    }

    public static function getInstanceDB(): PDO
    {
        return self::connexionDB();
    }

    public static function getDriver(): string
    {
        return self::$driver;
    }

    public static function query(PDO $pdo, string $sql, bool $single = true): array|false
    {
        $stmt = $pdo->query($sql);
        return $single ? $stmt->fetch() : $stmt->fetchAll();
    }

    public static function prepare(PDO $pdo, string $sql, array $datas = []): PDOStatement
    {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($datas);
        return $stmt;
    }

    public static function executeQuery(PDO $pdo, string $sql, array $datas = [], bool $single = true): array|false
    {
        $statement = self::prepare($pdo, $sql, $datas);
        return $single ? $statement->fetch() : $statement->fetchAll();
    }

    public static function executeUpdate(PDO $pdo, string $sql, array $datas = []): int
    {
        $statement = self::prepare($pdo, $sql, $datas);
        $trimSql = strtoupper(trim($sql));
        if (str_starts_with($trimSql, 'INSERT')) {
            return (int) $pdo->lastInsertId();
        }
        return $statement->rowCount();
    }

    public static function getAllTable(string $table): array
    {
        $pdo = self::connexionDB();
        $sql = "SELECT * FROM {$table}";
        $res = self::query($pdo, $sql, false);
        return is_array($res) ? $res : [];
    }
}
