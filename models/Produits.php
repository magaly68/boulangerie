<?php
require_once 'config/database.php';
$pdo = getPDO();

// Récupération de l'ID de la catégorie passée dans l'URL
$categorie_id = isset($_GET['categorie']) ? (int)$_GET['categorie'] : 0;

// Récupération du nom de la catégorie
$stmt = $pdo->prepare("SELECT nom FROM categories WHERE id = ?");
$stmt->execute([$categorie_id]);
$categorie = $stmt->fetch();

if (!$categorie) {
    echo "Catégorie introuvable.";
    exit;
}

// Récupération des produits associés
$stmt = $pdo->prepare("SELECT * FROM produits WHERE categorie_id = ?");
$stmt->execute([$categorie_id]);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Produits - <?= htmlspecialchars($categorie['nom']) ?></title>
</head>
<body>
    <h1>Produits dans la catégorie : <?= htmlspecialchars($categorie['nom']) ?></h1>
    <ul>
        <?php foreach ($produits as $produit): ?>
            <li>
                <strong><?= htmlspecialchars($produit['libelle']) ?></strong> - 
                <?= htmlspecialchars($produit['poids']) ?> - 
                <?= number_format($produit['prix'], 2, ',', ' ') ?> €
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="produits.php?categorie=1">Voir les pains</a>
</body>
</html>
