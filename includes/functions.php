<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function enregistrer_action(PDO $pdo, int $client_id, string $action) {
    $stmt = $pdo->prepare("INSERT INTO historique_actions (client_id, action) VALUES (?, ?)");
    $stmt->execute([$client_id, $action]);
}

function enregistrer_action_gestionnaire(PDO $pdo, int $gestionnaire_id, string $action) {
    $stmt = $pdo->prepare("INSERT INTO historique_gestionnaire (gestionnaire_id, action) VALUES (?, ?)");
    $stmt->execute([$gestionnaire_id, $action]);
}

function isClientLoggedIn(): bool {
    return isset($_SESSION['client_id']);
}

function isGestionnaireLoggedIn(): bool {
    return isset($_SESSION['gestionnaire_id']);
}

function ajouterFavori(PDO $pdo, int $client_id, int $boulangerie_id) { ... }
function supprimerFavori(PDO $pdo, int $client_id, int $boulangerie_id) { ... }
