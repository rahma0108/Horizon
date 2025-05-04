<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
use App\Controller\UtilisateurC;

if (!isset($_GET["id"]) || empty(trim($_GET["id"]))) {
    header('Location: backutilisateur.php?error=' . urlencode("❌ ID non spécifié."));
    exit();
}

$utilisateurC = new UtilisateurC();
try {
    $utilisateurC->deleteUtilisateurs(trim($_GET["id"]));
    header('Location: backutilisateur.php?success=' . urlencode("✅ Utilisateur supprimé avec succès !"));
    exit();
} catch (Exception $e) {
    header('Location: backutilisateur.php?error=' . urlencode("❌ Erreur lors de la suppression : " . $e->getMessage()));
    exit();
}