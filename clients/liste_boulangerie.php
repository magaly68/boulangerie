<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = getPDO();
$client_id = $_SESSION['client_id'];

// Récupérer tous les favoris du client
$stmt = $pdo->prepare("SELECT boulangerie_id FROM favoris WHERE client_id = ?");
$stmt->execute([$client_id]);
$favoris = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'boulangerie_id');

// Récupérer toutes les boulangeries
$stmt = $pdo->query("SELECT id, nom FROM boulangeries");
$boulangeries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Liste des boulangeries</h2>

<ul>
<?php foreach ($boulangeries as $b): ?>
    <li>
        <?= htmlspecialchars($b['nom']) ?>
        <?php if (in_array($b['id'], $favoris)): ?>
            <form action="retirer_favori.php" method="post" style="display:inline;">
                <input type="hidden" name="boulangerie_id" value="<?= $b['id'] ?>">
                <button type="submit">Retirer des favoris</button>
            </form>
        <?php else: ?>
            <form action="ajouter_favori.php" method="post" style="display:inline;">
                <input type="hidden" name="boulangerie_id" value="<?= $b['id'] ?>">
                <button type="submit">Ajouter aux favoris</button>
            </form>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
