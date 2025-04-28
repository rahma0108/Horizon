<?php
include '../config.php';

// Récupère les actualités non vues
$stmt = $pdo->prepare("SELECT * FROM actualites WHERE vu = 0");
$stmt->execute();
$nonVues = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ensuite, on les marque comme vues
$update = $pdo->prepare("UPDATE actualites SET vu = 1 WHERE vu = 0");
$update->execute();

// On retourne les actualités non vues avant mise à jour
echo json_encode(['success' => true, 'actualites' => $nonVues]);
