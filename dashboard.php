<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

if (!isGestionnaire()) {
    header('Location: index.php');
    exit;
}

$produits = getProduitsByBoulangerie($pdo, $_SESSION['user']['boulangerie_id']);
?>

<h1>Mon Tableau de bord</h1>
<ul>
<?php 
foreach ($produits as $produit) {
 ?>
    <li><?= htmlspecialchars($produit['libelle']) ?> - <?= $produit['prix'] ?> €</li>
<?php 
}
 ?>
</ul>
