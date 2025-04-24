<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['gestionnaire_id'])) {
    header("Location: connexion.php");
    exit;

    $pdo = getPDO();
$boulangerie_id = $_SESSION['boulangerie_id'];
$produit_id = $_GET['id'] ?? null;

if (!$produit_id) {
    header("Location: liste_produits.php");
    exit;
}

if (!$produit) {
    echo "Produit introuvable ou non autorisé.";
    exit;
}

// Supprimer la photo du serveur si elle existe
if ($produit['photo']) {
    $chemin_photo = '../' . $produit['photo'];
    if (file_exists($chemin_photo)) {
        unlink($chemin_photo);
    }
}


$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();

header("Location: liste_produits.php?succes=deleted");
exit;

enregistrer_action_gestionnaire($pdo, $_SESSION['gestionnaire_id'], "A ajouté le produit \"$nomProduit\"");
