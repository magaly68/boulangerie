<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = getPDO();

$client_id = $_SESSION['client_id'];
$boulangerie_id = $_POST['boulangerie_id'] ?? null;

if ($boulangerie_id) {
    $stmt = $pdo->prepare("DELETE FROM favoris WHERE client_id = ? AND boulangerie_id = ?");
    $stmt->execute([$client_id, $boulangerie_id]);
}

header('Location: liste_boulangeries.php');

enregistrer_action($pdo, $_SESSION['client_id'], "A supprimé la boulangerie \"$nomBoulangerie\" des favoris");
