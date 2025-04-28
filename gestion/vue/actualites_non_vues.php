<?php
include '../config.php';

$stmt = $pdo->prepare("SELECT * FROM actualites WHERE vu = 0 ORDER BY date_publication DESC");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
