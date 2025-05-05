<?php
session_start();
require_once '../config/database.php';

$pdo = getPDO();

$client_id = $_SESSION['client_id'] ?? null;

if ($client_id) {
    // Récupérer les ID des boulangeries favorites
    $stmt = $pdo->prepare("SELECT boulangerie_id FROM favoris WHERE client_id = ?");
    $stmt->execute([$client_id]);
    $favoris = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'boulangerie_id');
} else {
    $favoris = [];
}

// Construction de la requête produits
if (!empty($favoris)) {
    $placeholders = implode(',', array_fill(0, count($favoris), '?'));
    $query = "
        SELECT p.*, c.nom AS categorie_nom, b.nom AS boulangerie_nom
        FROM produits p
        JOIN categories c ON p.categorie_id = c.id
        JOIN boulangeries b ON p.boulangerie_id = b.id
        WHERE p.boulangerie_id IN ($placeholders)
        ORDER BY c.nom";
    $stmt = $pdo->prepare($query);
    $stmt->execute($favoris);
} else {
    $query = "
        SELECT p.*, c.nom AS categorie_nom, b.nom AS boulangerie_nom
        FROM produits p
        JOIN categories c ON p.categorie_id = c.id
        JOIN boulangeries b ON p.boulangerie_id = b.id
        ORDER BY c.nom";
    $stmt = $pdo->query($query);
}

$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Regrouper les produits par catégorie
$par_categorie = [];
foreach ($produits as $p) {
    $par_categorie[$p['categorie_nom']][] = $p;
}
?>

<h2>Nos produits <?php if ($client_id && $favoris) echo "dans vos boulangeries favorites"; ?></h2>

<?php 
foreach ($par_categorie as $categorie => $liste) { 
?>
    <h3><?= htmlspecialchars($categorie) ?></h3>
    <ul>
        <?php 
        foreach ($liste as $p) { 
        ?>
            <li>
                <strong><?= htmlspecialchars($p['libelle']) ?></strong> - <?= htmlspecialchars($p['prix']) ?> €  
                <em>(<?= htmlspecialchars($p['boulangerie_nom']) ?>)</em><br>
                <?= htmlspecialchars($p['description']) ?><br>
                <small>Mise à jour le <?= htmlspecialchars($p['updated_at']) ?></small>
            </li>
        <?php 
        } 
        ?>
        </ul> 
    <?php 
    } 
    ?>