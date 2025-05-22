
<?php

session_start();
if (!isset($_SESSION['client_id'])) {
    header('Location: connexion.php');
    exit;
}
?>
<?php
// Active l'affichage de toutes les erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

?>

<h2>Bienvenue sur votre espace client ! 🎉</h2>
<p><a href="connexion.php">Se connecter</a></p>
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Espace client</title>
</head>
<body>
    <p>
        <a href="favoris.php" target="_blank">Gérer mes boulangeries favorites</a>
        <a href="produits.php" target="_blank">Voir les produits</a>
        <a href="liste_boulangeries.php" target="_blank">Gérer mes favoris</a>
        <a href="mon_espace.php" target="_blank">Mon Espace</a>
    </p>

</body>
</html>