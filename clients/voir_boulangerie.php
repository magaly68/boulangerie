<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

session_start();

if (!isset($_SESSION['client_id'])) {
    header('Location: connexion_client.php');
    exit;
}

$client_id = $_SESSION['client_id'];

if (!isset($_GET['id'])) {
    echo "Aucune boulangerie sélectionnée.";
    exit;
}

$boulangerie_id = (int)$_GET['id'];

// Récupération des infos de la boulangerie
$stmt = $pdo->prepare("SELECT * FROM boulangeries WHERE id = ?");
$stmt->execute([$boulangerie_id]);
$boulangerie = $stmt->fetch();

if (!$boulangerie) {
    echo "Boulangerie introuvable.";
    exit;
}

// Vérifier si elle est déjà en favori
$stmt = $pdo->prepare("SELECT * FROM favoris WHERE client_id = ? AND boulangerie_id = ?");
$stmt->execute([$client_id, $boulangerie_id]);
$est_favori = $stmt->fetch() ? true : false;
?>

<h2><?= htmlspecialchars($boulangerie['nom']) ?></h2>
<p><strong>Ville :</strong> <?= htmlspecialchars($boulangerie['ville']) ?></p>
<p><strong>Description :</strong> <?= htmlspecialchars($boulangerie['description']) ?></p>

<?php if ($est_favori): ?>
    <p><a href="favoris.php?remove=<?= $boulangerie_id ?>">Retirer des favoris</a></p>
<?php else: ?>
    <p><a href="favoris.php?add=<?= $boulangerie_id ?>">Ajouter aux favoris</a></p>
<?php endif; ?>

<p><a href="mon_espace.php">Retour à mon espace</a></p>
