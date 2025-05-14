<?php
// includes/functions/produits.php

function getCategories(PDO $pdo) {
    $sql = "SELECT * FROM categories ORDER BY nom";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProduitsParCategorieEtBoulangerie(PDO $pdo, int $categorieId, int $boulangerieId) {
    $sql = "SELECT * FROM produits 
            WHERE categorie_id = :categorie_id AND boulangerie_id = :boulangerie_id
            ORDER BY nom";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'categorie_id' => $categorieId,
        'boulangerie_id' => $boulangerieId
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProduitsParCategorieToutesBoulangeries(PDO $pdo, int $categorieId) {
    $sql = "SELECT * FROM produits 
            WHERE categorie_id = :categorie_id 
            ORDER BY nom";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['categorie_id' => $categorieId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function ajouterProduit(PDO $pdo, array $data) {
    $sql = "INSERT INTO produits (nom, poids, photo, prix, description, date_mise_a_jour, categorie_id, boulangerie_id)
            VALUES (:nom, :poids, :photo, :prix, :description, NOW(), :categorie_id, :boulangerie_id)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}

function modifierProduit(PDO $pdo, int $produitId, array $data) {
    $sql = "UPDATE produits SET nom = :nom, poids = :poids, photo = :photo, prix = :prix, description = :description, 
            date_mise_a_jour = NOW(), categorie_id = :categorie_id 
            WHERE id = :id AND boulangerie_id = :boulangerie_id";
    $data['id'] = $produitId;
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($data);
}

function supprimerProduit(PDO $pdo, int $produitId, int $boulangerieId) {
    $sql = "DELETE FROM produits WHERE id = :id AND boulangerie_id = :boulangerie_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'id' => $produitId,
        'boulangerie_id' => $boulangerieId
    ]);
}

function getProduitById(PDO $pdo, int $idProduit) {
    $sql = "SELECT * FROM produits WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $idProduit]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
