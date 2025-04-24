-- Utilisateurs (clients + admins + gestionnaires)
CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'client', 'gestionnaire') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Boulangeries
CREATE TABLE boulangerie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Lien utilisateurs ↔︎ boulangeries (uniquement pour les gestionnaires)
CREATE TABLE utilsateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    bakery_id INT,
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(bakery_id) REFERENCES bakeries(id)
);

-- Catégories (fixes, créées par l'admin)
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Produits
CREATE TABLE produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    weight VARCHAR(50),
    image_path VARCHAR(255),
    price DECIMAL(6,2),
    description TEXT,
    updated_at DATETIME,
    category_id INT,
    bakery_id INT,
    INSERT INTO produits (libelle, poids, prix, categorie_id)
    VALUES 
    ('Baguette Tradition', '250g', 1.10, 1),
    ('Croissant', '80g', 0.90, 2),
    ('Éclair au chocolat', '150g', 2.50, 3),
    ('Sandwich jambon-beurre', '300g', 4.00, 4);
    ALTER TABLE produits ADD COLUMN boulangerie_id INT;

    FOREIGN KEY(category_id) REFERENCES categories(id),
    FOREIGN KEY(bakery_id) REFERENCES bakeries(id)
);

-- Favoris client ↔︎ boulangeries
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    bakery_id INT,
    FOREIGN KEY(user_id) REFERENCES users(id),
    FOREIGN KEY(bakery_id) REFERENCES bakeries(id)
);

CREATE TABLE gestionnaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    mot_de_passe VARCHAR(255) password_hash,
    boulangerie_id INT,
    FOREIGN KEY (boulangerie_id) REFERENCES boulangeries(id)
);

CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);

CREATE TABLE favoris (
    client_id INT,
    boulangerie_id INT,
    PRIMARY KEY (client_id, boulangerie_id),
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (boulangerie_id) REFERENCES boulangeries(id) ON DELETE CASCADE
);

CREATE TABLE historique_actions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    action TEXT NOT NULL,
    date_action DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

CREATE TABLE historique_gestionnaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gestionnaire_id INT NOT NULL,
    action TEXT NOT NULL,
    date_action DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (gestionnaire_id) REFERENCES gestionnaires(id) ON DELETE CASCADE
);


<?php
// config/database.php

function getPDO() {
    $host = 'localhost';
    $dbname = 'boulangerie_db'; // tu choisis le nom que tu veux
    $user = 'root'; // par défaut sur WAMP/XAMPP
    $password = ''; // souvent vide en local

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // pour les erreurs visibles
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}



