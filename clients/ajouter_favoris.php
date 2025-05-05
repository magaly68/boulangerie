<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Vérification de la connexion
if (function_exists('enregistrer_action')) {
    enregistrer_action($pdo, $_SESSION['client_id'], "A ajouté la boulangerie \"$nomBoulangerie\" en favori");
} else {
    error_log("Function 'enregistrer_action' is not defined.");
}

// Vérification de la session
if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}

// Connexion à la base de données
$pdo = getPDO();

$client_id = $_SESSION['client_id'];
$boulangerie_id = $_POST['boulangerie_id'] ?? null;

if ($boulangerie_id) {
    $stmt = $pdo->prepare("INSERT IGNORE INTO favoris (client_id, boulangerie_id) VALUES (?, ?)");
    $stmt->execute([$client_id, $boulangerie_id]);
}

header('Location: liste_boulangeries.php');
