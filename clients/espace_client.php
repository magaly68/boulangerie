<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header('Location: connexion.php');
    exit;
}
?>

<p>
    <a href="favoris.php">Gérer mes boulangeries favorites</a>
    <a href="produits.php">Voir les produits</a>
    <a href="liste_boulangeries.php">Gérer mes favoris</a>
    <a href="mon_espace.php">Mon Espace</a>

</p>

<h2>Bienvenue sur votre espace client ! 🎉</h2>
<p><a href="deconnexion.php">Se déconnecter</a></p>
