<?php

namespace Fiserv\Services;

use PDO;
use PDOException;

class DatabaseService
{
    private PDO $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO(
                sprintf(
                    "mysql:host=%s;dbname=%s",
                    ConfigService::get('database.host'),
                    ConfigService::get('database.database')
                ),
                ConfigService::get('database.username'),
                ConfigService::get('database.password')
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function fetchAll($sql, $params = []): array
    {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchOne($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }
}
