<?php
// Informations de connexion à la base de données
$host = "localhost";        // ou 127.0.0.1
$dbname = "greenmove"; // remplace par le vrai nom de ta base
$username = "root";         // ou ton nom d'utilisateur MySQL
$password = "";             // mot de passe, souvent vide en local (ex: XAMPP)

// Connexion avec PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Mode d'erreur en exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
