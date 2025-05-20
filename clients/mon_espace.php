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
    SELECT b.id, b.nom, b.ville
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

// Historique des actions
$stmt = $pdo->prepare("SELECT action, date_action FROM historique_actions WHERE client_id = ? ORDER BY date_action DESC LIMIT 10");
$stmt->execute([$client_id]);
$actions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace Client</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Montserrat', Arial, sans-serif;
        margin: 0;
        background: #f8f8f8;
        color: #222;
    }
    .dashboard-nav {
        background: #fff;
        border-bottom: 1px solid #eee;
        padding: 0.5em 0;
    }
    .dashboard-nav ul {
        display: flex;
        flex-wrap: wrap;
        gap: 1em;
        list-style: none;
        margin: 0;
        padding: 0 1em;
    }
    .dashboard-nav a {
        color: #2d6a4f;
        text-decoration: none;
        font-weight: 600;
        padding: 0.5em 1em;
        border-radius: 4px;
    }
    .dashboard-nav a:focus,
    .dashboard-nav a:hover {
        background: #d8f3dc;
        outline: 2px solid #40916c;
    }
    .dashboard-main {
        max-width: 900px;
        margin: 2em auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        padding: 2em;
    }
    @media (max-width: 700px) {
        .dashboard-main {
            padding: 1em;
        }
        .dashboard-nav ul {
            flex-direction: column;
            gap: 0.5em;
        }
    }
    :focus {
        outline: 2px solid #40916c;
        outline-offset: 2px;
    }
    </style>
</head>
<body>
<nav aria-label="Tableau de bord" class="dashboard-nav">
    <ul>
        <li><a href="#infos" accesskey="i">Mes infos</a></li>
        <li><a href="#favoris" accesskey="f">Favoris</a></li>
        <li><a href="#produits" accesskey="p">Produits</a></li>
        <li><a href="#historique" accesskey="h">Historique</a></li>
    </ul>
</nav>
<main class="dashboard-main" tabindex="-1">
    <h2 id="infos">Mon Espace</h2>
    <ul>
        <li><a href="modifier_client.php">Modifier mes informations</a></li>
    </ul>
    <p>
        <strong>Email :</strong>
        <?= htmlspecialchars($client['email']) ?>
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
    <h3 id="favoris">⭐ Mes boulangeries favorites</h3>
    <ul>
        <?php if (empty($favoris)): ?>
            <li>Aucune pour le moment.</li>
        <?php else:
            foreach ($favoris as $b): ?>
                <li><?= htmlspecialchars($b['nom']) ?><?= isset($b['ville']) ? ' (' . htmlspecialchars($b['ville']) . ')' : '' ?>
                    - <a href="voir_boulangerie.php?id=<?= $b['id'] ?>">Voir la boulangerie</a>
                </li>
            <?php endforeach;
        endif; ?>
    </ul>
    <h3 id="produits">🍞 Produits de mes boulangeries favorites</h3>
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
                <?= htmlspecialchars($p['prix']) ?> €<br/>
                <em>
                    <?= htmlspecialchars($p['boulangerie']) ?>
                </em>
                <br/>
                <?= htmlspecialchars($p['description']) ?>
            </li>
        <?php
            }
        }
        ?>
    </ul>
    <h3 id="historique">🕒 Historique des actions</h3>
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
</main>
</body>
</html>

