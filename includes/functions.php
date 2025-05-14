<?php
// Démarrage de la session (si ce n'est pas déjà fait)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Chargement de la configuration (connexion à la base de données)
require_once __DIR__ . '/db.php';

// Inclusion des fichiers de fonctions organisés par fonctionnalité
require_once __DIR__ . '/functions/utilisateurs.php';
require_once __DIR__ . '/functions/produits.php';
require_once __DIR__ . '/functions/categories.php';
require_once __DIR__ . '/functions/boulangeries.php';
require_once __DIR__ . '/functions/favoris.php';
require_once __DIR__ . '/functions/historique.php';
require_once __DIR__ . '/functions/commandes.php';
// Ajoute ici les nouveaux fichiers au fur et à mesure si besoin

// (Facultatif) Configuration de la timezone
date_default_timezone_set('Europe/Paris');
