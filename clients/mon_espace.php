<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php'; // ajoute cette ligne si tu ne l’as pas encore

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
    WHERE f.client_id = ?
");
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
        ORDER BY b.nom
    ");
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php
require_once 'includes/functions.php'; // si ce n'est pas déjà fait
$client_id = $_SESSION['client_id'] ?? null;


<h2>Mon Espace</h2>

<p><strong>Email :</strong> <?= htmlspecialchars($client['email']) ?></p>
<p><a href="modifier_profil.php">Modifier mon profil</a></p>
<p><a href="liste_boulangeries.php">Gérer mes favoris</a></p>
<p><a href="logout.php">Se déconnecter</a></p>

<hr>

<h3>⭐ Mes boulangeries favorites</h3>
<ul>
    <?php if (empty($favoris)): ?>
        <li>Aucune pour le moment.</li>
    <?php else: ?>
        <?php foreach ($favoris as $b): ?>
            <li><?= htmlspecialchars($b['nom']) ?></li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>

<hr>

<h3>🍞 Produits de mes boulangeries favorites</h3>
<ul>
    <?php if (empty($produits)): ?>
        <li>Aucun produit trouvé.</li>
    <?php else: ?>
        <?php foreach ($produits as $p): ?>
            <li>
                <strong><?= htmlspecialchars($p['libelle']) ?></strong> - <?= htmlspecialchars($p['prix']) ?> €<br>
                <em><?= htmlspecialchars($p['boulangerie']) ?></em><br>
                <?= htmlspecialchars($p['description']) ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>

<hr>
<h3>🕒 Historique des actions</h3>
<!-- Affichage de l'historique -->
...

<?php
$stmt = $pdo->prepare("SELECT action, date_action FROM historique_actions WHERE client_id = ? ORDER BY date_action DESC LIMIT 10");
$stmt->execute([$client_id]);
$actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<ul>
<?php if (empty($actions)): ?>
    <li>Aucune action enregistrée.</li>
<?php else: ?>
    <?php foreach ($actions as $a): ?>
        <li><?= htmlspecialchars($a['action']) ?> <br><small><?= $a['date_action'] ?></small></li>
    <?php endforeach; ?>
<?php endif; ?>
</ul>

<hr>
<h3>🛠️ Historique des actions</h3>

<?php
$stmt = $pdo->prepare("SELECT action, date_action FROM historique_gestionnaire WHERE gestionnaire_id = ? ORDER BY date_action DESC LIMIT 10");
$stmt->execute([$_SESSION['gestionnaire_id']]);
$actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<ul>
<?php if (empty($actions)): ?>
    <li>Aucune action enregistrée.</li>
<?php else: ?>
    <?php foreach ($actions as $a): ?>
        <li><?= htmlspecialchars($a['action']) ?> <br><small><?= $a['date_action'] ?></small></li>
    <?php endforeach; ?>
<?php endif; ?>
</ul>

<hr>
<h3>🛠️ Historique des actions</h3>

<?php
$stmt = $pdo->prepare("SELECT action, date_action FROM historique_gestionnaire WHERE gestionnaire_id = ? ORDER BY date_action DESC LIMIT 10");
$stmt->execute([$_SESSION['gestionnaire_id']]);
$actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<ul>
<?php if (empty($actions)): ?>
    <li>Aucune action enregistrée.</li>
<?php else: ?>
    <?php foreach ($actions as $a): ?>
        <li><?= htmlspecialchars($a['action']) ?> <br><small><?= $a['date_action'] ?></small></li>
    <?php endforeach; ?>
<?php endif; ?>
</ul>

<h3>🕒 Historique des actions</h3>

<?php
$stmt = $pdo->prepare("SELECT action, date_action FROM historique_gestionnaire WHERE gestionnaire_id = ? ORDER BY date_action DESC LIMIT 10");
$stmt->execute([$_SESSION['gestionnaire_id']]);
$actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<ul>
    <?php if (empty($actions)): ?>
        <li>Aucune action enregistrée.</li>
    <?php else: ?>
        <?php foreach ($actions as $a): ?>
            <li>
                <?= htmlspecialchars($a['action']) ?><br>
                <small><?= $a['date_action'] ?></small>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>

