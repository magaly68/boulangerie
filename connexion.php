<?php
session_start();
require_once 'config/database.php';
$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM gestionnaires WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        $_SESSION['gestionnaire_id'] = $user['id'];
        $_SESSION['boulangerie_id'] = $user['boulangerie_id'];
        header("Location: liste_produits.php");
        exit;
    } else {
        $erreur = "Identifiants incorrects";
    }
}
?>

<h2>Connexion</h2>
<?php if (!empty($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>
<form method="post">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br>
    <button type="submit">Se connecter</button>
</form>
