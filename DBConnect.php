<?php

// Charge le fichier autoload.php généré par Composer.
require_once __DIR__ . '/vendor/autoload.php';
// Classe responsable de la connexion à la base de données.
class DBConnect
{
    // Contient l'objet PDO représentant la connexion à MySQL.
    // Le '?' signifie que la propriété peut également contenir null
    // si la connexion échoue.
    private ?PDO $pdo = null;

    public function __construct()
    {
        try {
            // Charge le fichier .env
            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'');
            $dotenv->load();

            // Récupère les informations de connexion à la base de données depuis le fichier .env
            $host = $_ENV['DB_HOST'];
            $dbname = $_ENV['DB_NAME'];
            $username = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASSWORD'];

            // Création de la connexion PDO à MySQL.
            // PDO permet à PHP de communiquer avec la base de données.
            //
            // charset=utf8mb4 permet notamment de gérer correctement
            // les caractères accentués et les caractères Unicode.
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password
            );

            // Configure PDO pour qu'une erreur SQL déclenche
            // une exception PDOException.
            // Cela permet ensuite de gérer les erreurs avec try/catch.
            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch (PDOException $e) {

            // Si la connexion échoue, on affiche le message d'erreur.
            echo "Erreur de connexion : " . $e->getMessage() . "\n";
        }
    }

    // Retourne la connexion PDO afin que les autres classes
    // puissent communiquer avec la base de données.
    //
    // La méthode retourne soit un objet PDO,
    // soit null si la connexion n'a pas pu être établie.
    public function getPDO(): ?PDO
    {
        return $this->pdo;
    }
}