<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

session_start();

// Vérifie si le client est connecté
if (!isset($_SESSION['client_id'])) {
    header('Location: connexion_client.php');
    exit;
}

$client_id = $_SESSION['client_id'];
$message = '';

// Ajouter une boulangerie aux favoris
if (isset($_GET['add'])) {
    $boulangerie_id = (int)$_GET['add'];

    // Vérifie si ce favori existe déjà
    $stmt = $pdo->prepare("SELECT * FROM favoris WHERE client_id = ? AND boulangerie_id = ?");
    $stmt->execute([$client_id, $boulangerie_id]);
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO favoris (client_id, boulangerie_id) VALUES (?, ?)");
        $stmt->execute([$client_id, $boulangerie_id]);
        $message = "Boulangerie ajoutée aux favoris.";
    } else {
        $message = "Cette boulangerie est déjà dans vos favoris.";
    }
}

// Supprimer une boulangerie des favoris
if (isset($_GET['remove'])) {
    $boulangerie_id = (int)$_GET['remove'];
    $stmt = $pdo->prepare("DELETE FROM favoris WHERE client_id = ? AND boulangerie_id = ?");
    $stmt->execute([$client_id, $boulangerie_id]);
    $message = "Boulangerie retirée des favoris.";
}

// Récupérer la liste des favoris
$stmt = $pdo->prepare("
    SELECT b.id, b.nom, b.ville
    FROM favoris f
    JOIN boulangeries b ON f.boulangerie_id = b.id
    WHERE f.client_id = ?
");
$stmt->execute([$client_id]);
$favoris = $stmt->fetchAll();
?>

<h2>Mes boulangeries favorites</h2>

<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<?php if (count($favoris) > 0): ?>
    <ul>
        <?php foreach ($favoris as $boulangerie): ?>
            <li>
                <?= htmlspecialchars($boulangerie['nom']) ?> - <?= htmlspecialchars($boulangerie['ville']) ?>
                <a href="?remove=<?= $boulangerie['id'] ?>">Retirer</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Vous n'avez encore aucune boulangerie en favori.</p>
<?php endif; ?>

<p><a href="mon_espace.php">Retour à mon espace</a></p>
