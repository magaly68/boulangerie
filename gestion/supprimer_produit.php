<?php
session_start();
require_once 'config/database.php';
require_once 'utils/gestionnaire_actions.php'; 

if (!isset($_SESSION['gestionnaire_id'])) {
    header("Location: connexion.php");
    exit;
}

$pdo = getPDO();
$boulangerie_id = $_SESSION['boulangerie_id'];
$produit_id = $_GET['id'] ?? null;

if (!$produit_id) {
    header("Location: liste_produits.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ? AND boulangerie_id = ?");
$stmt->execute([$produit_id, $boulangerie_id]);
$produit = $stmt->fetch();

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

function enregistrer_action_gestionnaire($pdo, $gestionnaire_id, $action) {
    $stmt = $pdo->prepare("INSERT INTO gestionnaire_actions (gestionnaire_id, action, date_action) VALUES (?, ?, NOW())");
    $stmt->execute([$gestionnaire_id, $action]);
}

// Enregistrer l'action du gestionnaire
enregistrer_action_gestionnaire(
    $pdo,
    $_SESSION['gestionnaire_id'],
    "A supprimé le produit \"{$produit['nom']}\""
);



header("Location: liste_produits.php?succes=deleted");
exit;
