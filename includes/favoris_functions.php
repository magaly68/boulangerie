<?php
// includes/functions/favoris.php

function ajouterFavori(PDO $pdo, int $clientId, int $boulangerieId) {
    $sql = "INSERT INTO favoris (client_id, boulangerie_id)
            VALUES (:client_id, :boulangerie_id)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'client_id' => $clientId,
        'boulangerie_id' => $boulangerieId
    ]);
}

function supprimerFavori(PDO $pdo, int $clientId, int $boulangerieId) {
    $sql = "DELETE FROM favoris WHERE client_id = :client_id AND boulangerie_id = :boulangerie_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'client_id' => $clientId,
        'boulangerie_id' => $boulangerieId
    ]);
}

function estFavori(PDO $pdo, int $clientId, int $boulangerieId) {
    $sql = "SELECT COUNT(*) FROM favoris WHERE client_id = :client_id AND boulangerie_id = :boulangerie_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'client_id' => $clientId,
        'boulangerie_id' => $boulangerieId
    ]);
    return $stmt->fetchColumn() > 0;
}

function getFavorisParClient(PDO $pdo, int $clientId) {
    $sql = "SELECT b.* FROM favoris f
            JOIN boulangeries b ON f.boulangerie_id = b.id
            WHERE f.client_id = :client_id
            ORDER BY b.nom";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['client_id' => $clientId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
