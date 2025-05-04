<?php
namespace App\Model;

class Databaseconfig {
    private static $connexion = null;

    public static function getConnexion() {
        if (self::$connexion === null) {
            try {
                $dsn = 'mysql:host=localhost;dbname=greenmove;charset=utf8';
                self::$connexion = new \PDO($dsn, 'root', '', [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]);
                echo "Debug: Connexion à la base de données établie<br>";
            } catch (\PDOException $e) {
                throw new \Exception("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
        return self::$connexion;
    }
}