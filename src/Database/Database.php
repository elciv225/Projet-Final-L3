<?php

namespace System\Database;


use PDO;
use PDOException;
use Dotenv\Dotenv;

class Database
{
    private static ?PDO $pdo = null;

    private function __construct()
    {
        // Constructeur privé pour empêcher l'instanciation directe
    }

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            // Charger les variables d'environnement
            $dotenv = Dotenv::createImmutable(BASE_PATH);
            $dotenv->load();

            $dbHost = $_ENV['DB_HOST'];
            $dbName = $_ENV['DB_NAME'];
            $dbUser = $_ENV['DB_USER'];
            $dbPassword = $_ENV['DB_PASSWORD'];

            try {
                // Établir la connexion
                self::$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPassword);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                error_log("Erreur de connexion à la base de données : " . $e->getMessage());
                die("Database.php => Une erreur est survenue. Veuillez réessayer plus tard.");
            }
        }

        return self::$pdo;
    }
}