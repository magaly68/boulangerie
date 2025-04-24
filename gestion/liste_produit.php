<?php
require_once 'config/database.php';
$pdo = getPDO();

$stmt = $pdo->query("SELECT produits.*, categories.nom AS categorie FROM produits
JOIN categories ON produits.categorie_id = categories.id");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

$stmt = $pdo->prepare("SELECT produits.*, categories.nom AS categorie
    FROM produits
    JOIN categories ON produits.categorie_id = categories.id
    WHERE produits.boulangerie_id = ?");
$stmt->execute([$boulangerie_id]);


<h2>Liste des produits</h2>
<a href="ajouter_produit.php">➕ Ajouter un produit</a>
<ul>
    <?php foreach ($produits as $prod): ?>
        <a href="modifier_produit.php?id=<?= $p['id'] ?>">Modifier</a> | 
        <a href="supprimer_produit.php?id=<?= $p['id'] ?>" onclick="return confirm('Supprimer ce produit ?');">Supprimer</a>

        <li>
            <strong><?= htmlspecialchars($prod['libelle']) ?></strong>
            (<?= htmlspecialchars($prod['categorie']) ?>) - 
            <?= number_format($prod['prix'], 2, ',', ' ') ?> €
            [<a href="modifier_produit.php?id=<?= $prod['id'] ?>">✏️ Modifier</a>] 
            [<a href="supprimer_produit.php?id=<?= $prod['id'] ?>" onclick="return confirm('Supprimer ce produit ?')">🗑️ Supprimer</a>]
        </li>
    <?php endforeach; ?>
</ul>

