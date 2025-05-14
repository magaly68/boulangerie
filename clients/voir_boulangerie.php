<?php
require_once '../config/database.php';
require_once '../includes/functions_favoris.php';
require_once '../includes/functions_boulangeries.php';
session_start();

;

if (!isset($_GET['id']) {
    echo "Aucune boulangerie sélectionnée.";
    exit;
}

$id_boulangerie = (int)$_GET['id']);
$client_id = $_SESSION['client']['id'] ?? null

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_favori']) && $client_id) {
    if ($_POST['action_favori'] === 'ajouter') {
        ajouterFavori($pdo, $client_id, $boulangerie_id);
    } elseif ($_POST['action_favori'] === 'retirer') {
        supprimerFavori($pdo, $client_id, $boulangerie_id);
    }
    // Redirection pour éviter la double soumission
    header("Location: voir_boulangerie.php?id=" . $boulangerie_id);
    exit;
}

$boulangerie = getBoulangerieById($pdo, $boulangerie_id);
$est_favori = false;


if ($client_id) {
    $est_favori = estFavori($pdo, $client_id, $boulangerie_id);
}
// Récupérer les infos de la boulangerie
$stmt = $pdo->prepare("SELECT * FROM boulangeries WHERE id = ?");
$stmt->execute([$id_boulangerie]);
$boulangerie = $stmt->fetch();

if (!$boulangerie) {
    echo "Boulangerie introuvable.";
    exit;
}
?>

<h1><?= htmlspecialchars($boulangerie['nom']) ?></h1>
<p><strong>Adresse :</strong> <?= htmlspecialchars($boulangerie['adresse']) ?></p>
<p><strong>Description :</strong> <?= nl2br(htmlspecialchars($boulangerie['description'])) ?></p>
<p><strong>Horaires :</strong> <?= htmlspecialchars($boulangerie['horaires']) ?></p>
<p><strong>Téléphone :</strong> <?= htmlspecialchars($boulangerie['telephone']) ?></p>





// Vérification de l'ID passé en GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id_boulangerie = (int) $_GET['id'];

// Récupération des infos de la boulangerie
$stmt = $pdo->prepare("SELECT * FROM boulangeries WHERE id = ?");
$stmt->execute([$id_boulangerie]);
$boulangerie = $stmt->fetch();

if (!$boulangerie) {
    echo "<p>Boulangerie non trouvée.</p>";
    exit();
}

<h2>Catégories disponibles</h2>
<ul>
<?php
$query = "
    SELECT DISTINCT c.id, c.nom
    FROM categories c
    INNER JOIN produits p ON p.categorie_id = c.id
    WHERE p.boulangerie_id = ?
    ORDER BY c.nom
";
$stmt = $pdo->prepare($query);
$stmt->execute([$id_boulangerie]);
$categories = $stmt->fetchAll();

if ($categories) {
    foreach ($categories as $cat) {
        echo "<li><a href='#cat" . htmlspecialchars($cat['id']) . "'>" . htmlspecialchars($cat['nom']) . "</a></li>";
    }
} else {
    echo "<li>Aucune catégorie trouvée.</li>";
}
?>
</ul>


// Récupération des catégories disponibles
$categories = getCategories($pdo);

// Récupération des produits par catégorie
$produits_par_categorie = [];

foreach ($categories as $categorie) {
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id_boulangerie = ? AND categorie = ?");
    $stmt->execute([$id_boulangerie, $categorie]);
    $produits_par_categorie[$categorie] = $stmt->fetchAll();
}
?>

<?php
foreach ($categories as $cat) {
    echo "<h3 id='cat{$cat['id']}'>" . htmlspecialchars($cat['nom']) . "</h3>";

    $stmt = $pdo->prepare("
        SELECT * FROM produits 
        WHERE boulangerie_id = ? AND categorie_id = ?
    ");
    $stmt->execute([$id_boulangerie, $cat['id']]);
    $produits = $stmt->fetchAll();

    if ($produits) {
        echo "<ul>";
        foreach ($produits as $prod) {
            echo "<li>";
            echo "<strong>" . htmlspecialchars($prod['libelle']) . "</strong> - " . htmlspecialchars($prod['prix']) . "€<br>";
            echo htmlspecialchars($prod['description']);
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucun produit dans cette catégorie.</p>";
    }
}
?>


<h1><?= htmlspecialchars($boulangerie['nom']) ?></h1>
<p><strong>Adresse :</strong> <?= htmlspecialchars($boulangerie['adresse']) ?></p>
<p><strong>Description :</strong> <?= htmlspecialchars($boulangerie['description']) ?></p>

<?php 
foreach ($produits_par_categorie as $categorie => $produits): 
    ?>
    <h2><?= ucfirst($categorie) ?></h2>
    <?php 
    if (count($produits) > 0): 
        ?>
        <ul>
            <?php 
            foreach ($produits as $produit): 
                ?>
                <li>
                    <strong><?= htmlspecialchars($produit['libelle']) ?></strong> - <?= $produit['prix'] ?> €<br>
                    <?= htmlspecialchars($produit['description']) ?>
                </li>
            <?php 
        endforeach; 
        ?>
        </ul>
    <?php 
    else: 
    ?>
        <p>Aucun produit dans cette catégorie.</p>
    <?php 
    endif; 
    ?>
    <?php 
    endforeach; 
    ?>

<?php
if ($client_id):
    ?>
    <form method="post" action="voir_boulangerie.php?id=<?= $boulangerie_id ?>">
        <input type="hidden" name="action_favori" value="<?= $est_favori ? 'retirer' : 'ajouter' ?>">
        <button type="submit">
            <?= $est_favori ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>
        </button>
    </form>
<?php
endif;
?>

