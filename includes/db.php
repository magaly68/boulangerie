<?php
$servername = "localhost";
$username = "root";
$password = "mot_de_passe";
$dbname = "boulangerie";
$charset = "utf8mb4";

$dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

 try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Erreur de connexion à la base de données : " . $e->getMessage());
        // En développement, vous pouvez afficher l'erreur pour déboguer :
        // die("Erreur de connexion à la base de données: " . $e->getMessage());
        // En production, affichez un message générique et logguez l'erreur
        die("Une erreur est survenue lors de la connexion à la base de données.");
    }
// Connexion via PDO établie ci-dessus, aucune connexion mysqli nécessaire.
?>
