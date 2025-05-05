<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php'; 

// Vérification de la session
if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}


$pdo = getPDO();
$client_id = $_SESSION['client_id'];

// Infos client
$stmt = $pdo->prepare("SELECT email FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

// Boulangeries favorites
$stmt = $pdo->prepare("
    SELECT b.id, b.nom
    FROM favoris f
    JOIN boulangeries b ON f.boulangerie_id = b.id
    WHERE f.client_id = ?");
$stmt->execute([$client_id]);
$favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Produits des boulangeries favorites
$produits = [];
if (!empty($favoris)) {
    $ids = implode(',', array_column($favoris, 'id'));
    $stmt = $pdo->query("
        SELECT p.libelle, p.prix, p.description, b.nom AS boulangerie
        FROM produits p
        JOIN boulangeries b ON p.boulangerie_id = b.id
        WHERE p.boulangerie_id IN ($ids)
        ORDER BY b.nom");
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php
require_once 'includes/functions.php'; // si ce n'est pas déjà fait
$client_id = $_SESSION['client_id'] ?? null;
?>

<h2>Mon Espace</h2>

<ul>
    <li><a href="modifier_client.php">Modifier mes informations</a></li>
   
</ul>
<p>
    <strong>Email :</strong> 
    <?= htmlspecialchars($client['email']) 
    ?>
</p>
<p>
    <a href="modifier_profil.php">Modifier mon profil</a>
</p>
<p>
    <a href="liste_boulangeries.php">Gérer mes favoris</a>
</p>
<p>
    <a href="logout.php">Se déconnecter</a>
</p>

<hr>

<h3>⭐ Mes boulangeries favorites</h3>
<ul>
    <?php 
        if (empty($favoris)): ?>
        <li>Aucune pour le moment.</li>
    <?php 
        else: 
            foreach ($favoris as $b): ?>
                <li><?= htmlspecialchars($b['nom']) ?></li>
            <?php 
            endforeach; 
        endif;
    ?>
</ul>


<hr>
<?php
$stmt = $pdo->prepare("
    SELECT b.id, b.nom, b.ville 
    FROM favoris f 
    JOIN boulangeries b ON f.boulangerie_id = b.id 
    WHERE f.client_id = ?
");
$stmt->execute([$_SESSION['client_id']]);
$favorites = $stmt->fetchAll();

if ($favorites):
?>
    <ul>
    <?php foreach ($favorites as $boulangerie): ?>
        <li>
            <?= htmlspecialchars($boulangerie['nom']) ?> (<?= htmlspecialchars($boulangerie['ville']) ?>)
            - <a href="voir_boulangerie.php?id=<?= $boulangerie['id'] ?>">Voir la boulangerie</a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucune boulangerie en favori pour le moment.</p>
<?php endif; ?>
<h3>🍞 Produits de mes boulangeries favorites</h3>
<ul>
    <?php 
    if (empty($produits)) { 
        ?>
        <li>Aucun produit trouvé.</li>
    <?php 
    } else {
        foreach ($produits as $p) { 
        ?>
        <li>
            <strong><?= htmlspecialchars($p['libelle']) ?></strong> 
             <?= htmlspecialchars($p['prix']) 
             ?> €<br/>
            <em>
                <?= htmlspecialchars($p['boulangerie']) 
            ?>
            </em>
            <br/>
            <?= htmlspecialchars($p['description']) 
            ?>
        </li>
    <?php 
    } 
    } 
    ?>
</ul>

<h3>🕒 Historique des actions</h3>

<?php
$stmt = $pdo->prepare("SELECT action, date_action FROM historique_actions WHERE client_id = ? ORDER BY date_action DESC LIMIT 10");
$stmt->execute([$client_id]);
$actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<ul>
<?php 
if (empty($actions)) { 
    ?>
    <li>Aucune action enregistrée.</li>
<?php 
} else {
    foreach ($actions as $a) { 
        ?>
        <li>
            <?= htmlspecialchars($a['action']) ?> <br>
            <small><?= htmlspecialchars($a['date_action']) ?></small>
        </li>
    <?php 
    } 
} 
?>
</ul>

<?php
// Récupérer les produits par catégorie pour cette boulangerie
$stmtCat = $pdo->prepare("SELECT id, nom FROM categories");
$stmtCat->execute();
$categories = $stmtCat->fetchAll();

foreach ($categories as $cat):
    $stmtProd = $pdo->prepare("
        SELECT libelle, poids, photo, prix, description 
        FROM produits 
        WHERE categorie_id = ? AND boulangerie_id = ?
    ");
    $stmtProd->execute([$cat['id'], $boulangerie['id']]);
    $produits = $stmtProd->fetchAll();

    if ($produits):
?>
    <h4>Catégorie : <?= htmlspecialchars($cat['nom']) ?></h4>
    <ul>
    <?php foreach ($produits as $prod): ?>
        <li>
            <strong><?= htmlspecialchars($prod['libelle']) ?></strong> - <?= htmlspecialchars($prod['poids']) ?>g - <?= number_format($prod['prix'], 2) ?>€
            <br>
            <?= !empty($prod['description']) ? htmlspecialchars($prod['description']) : '' ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php
    endif;
endforeach;
?>

<?php
// Récupérer les produits par catégorie pour cette boulangerie
$stmtCat = $pdo->prepare("SELECT id, nom FROM categories");
$stmtCat->execute();
$categories = $stmtCat->fetchAll();

foreach ($categories as $cat):
    $stmtProd = $pdo->prepare("
        SELECT id, libelle, poids, photo, prix, description 
        FROM produits 
        WHERE categorie_id = ? AND boulangerie_id = ?
    ");
    $stmtProd->execute([$cat['id'], $boulangerie['id']]);
    $produits = $stmtProd->fetchAll();

    if ($produits):
?>
    <h4>Catégorie : <?= htmlspecialchars($cat['nom']) ?></h4>
    <ul>
    <?php foreach ($produits as $prod): ?>
        <li>
            <strong><?= htmlspecialchars($prod['libelle']) ?></strong> - <?= htmlspecialchars($prod['poids']) ?>g - <?= number_format($prod['prix'], 2) ?>€
            <br>
            <?= !empty($prod['description']) ? htmlspecialchars($prod['description']) : '' ?>
            <br>
            <?php if (!empty($prod['photo'])): ?>
                <img src="uploads/<?= htmlspecialchars($prod['photo']) ?>" alt="<?= htmlspecialchars($prod['libelle']) ?>" style="width: 100px; height: auto; margin-top: 5px;">
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php
    endif;
endforeach;
?>

