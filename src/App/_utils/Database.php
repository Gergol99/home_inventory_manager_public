<?php

namespace App\_utils;

class Database {
    public function getConnection() {
        try {
            $dsn = "mysql:host=mysql;dbname=home_inventory;charset=utf8;port=3306";
            $user = "root";
            $password = "secret";
            $conn = new \PDO($dsn, $user, $password);
        } catch (\PDOException $e) {
            var_dump($e);
        }
        return $conn;
    }
}