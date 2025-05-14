<?php

function createClient(PDO $pdo, string $email, string $motDePasse) {
    $hashedPassword = password_hash($motDePasse, PASSWORD_DEFAULT);
    $sql = "INSERT INTO clients (email, mot_de_passe) VALUES (:email, :mot_de_passe)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'email' => $email,
        'mot_de_passe' => $hashedPassword
    ]);
}

function getClientByEmail(PDO $pdo, string $email) {
    $sql = "SELECT * FROM clients WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function updateClientEmail( PDO $pdo, int $idClient, $email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE clients SET email = ?, mot_de_passe = ? WHERE id = ?");
    return $stmt->execute([$email, $password, $id]);
}

