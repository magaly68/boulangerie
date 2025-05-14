<?php
// includes/functions/boulangeries.php

function getToutesBoulangeries(PDO $pdo) {
    $sql = "SELECT * FROM boulangeries ORDER BY nom";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getBoulangerieById(PDO $pdo, int $id) {
    $sql = "SELECT * FROM boulangeries WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function ajouterBoulangerie(PDO $pdo, array $data) {
    $sql = "INSERT INTO boulangeries (nom, adresse, ville, code_postal, description, logo, utilisateur_id)
            VALUES (:nom, :adresse, :ville, :code_postal, :description, :logo, :utilisateur_id)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}

function modifierBoulangerie(PDO $pdo, int $id, array $data) {
    $sql = "UPDATE boulangeries SET nom = :nom, adresse = :adresse, ville = :ville, code_postal = :code_postal,
            description = :description, logo = :logo
            WHERE id = :id AND utilisateur_id = :utilisateur_id";
    $data['id'] = $id;
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}

function supprimerBoulangerie(PDO $pdo, int $id, int $utilisateur_id) {
    $sql = "DELETE FROM boulangeries WHERE id = :id AND utilisateur_id = :utilisateur_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'id' => $id,
        'utilisateur_id' => $utilisateur_id
    ]);
}
