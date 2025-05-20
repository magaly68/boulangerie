<?php
session_start();
require_once '../config/database.php';
require_once '../utils/functions.php'; // Ensure this file contains the 'enregistrer_action' function
if (!function_exists('enregistrer_action')) {
    function enregistrer_action($pdo, $client_id, $action) {
        $stmt = $pdo->prepare("INSERT INTO actions_log (client_id, action, date) VALUES (?, ?, NOW())");
        $stmt->execute([$client_id, $action]);
    }
}

if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = getPDO();

$client_id = $_SESSION['client_id'];
$boulangerie_id = $_POST['boulangerie_id'] ?? null;

if ($boulangerie_id) {
    $stmt = $pdo->prepare("DELETE FROM favoris WHERE client_id = ? AND boulangerie_id = ?");
        if (isset($nomBoulangerie)) {
            enregistrer_action($pdo, $_SESSION['client_id'], "A supprimé la boulangerie \"$nomBoulangerie\" des favoris");
        } else {
            error_log("Variable 'nomBoulangerie' is not defined.");
        }
}

header('Location: liste_boulangeries.php');
if (function_exists('enregistrer_action')) {
    if (function_exists('enregistrer_action')) {
        enregistrer_action($pdo, $_SESSION['client_id'], "A supprimé la boulangerie \"$nomBoulangerie\" des favoris");
    } else {
        error_log("Function 'enregistrer_action' is not defined.");
    }
} else {
    error_log("Function 'enregistrer_action' is not defined.");
}
enregistrer_action($pdo, $_SESSION['client_id'], "A supprimé la boulangerie \"$nomBoulangerie\" des favoris");
header('mon_espace.php');
exit;

?>