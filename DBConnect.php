<?php

class DBConnect
{
    private ?PDO $pdo = null;

    public function __construct()
    {
        try {
            $host = 'localhost';
            $dbname = 'adress_book';
            $username = 'root';
            $password = '';

            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password
            );

            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage() . "\n";
        }
    }

    public function getPDO(): ?PDO
    {
        return $this->pdo;
        
    }
}