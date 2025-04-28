<?php
include '../config.php';

// Compter uniquement les actualités non vues et déjà publiées
$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM actualites WHERE vu = 0 AND date_publication <= NOW()");
$stmt->execute();
$row = $stmt->fetch();

echo json_encode(['non_vues' => $row['total']]);
