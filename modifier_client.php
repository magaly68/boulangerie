<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

if (!isClient()) {
    header('Location: index.php');
    exit;
}

$client_id = $_SESSION['user']['id'];
$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mdp_actuel = $_POST['mdp_actuel'] ?? '';
    $nouveau_mdp = $_POST['nouveau_mdp'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';

    // Vérifier l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Email invalide.";
    }

    // Vérifier le mot de passe actuel
    $stmt = $pdo->prepare("SELECT mot_de_passe FROM clients WHERE id = ?");
    $stmt->execute([$client_id]);
    $client = $stmt->fetch();

    if (!$client || !password_verify($mdp_actuel, $client['mot_de_passe'])) {
        $erreurs[] = "Mot de passe actuel incorrect.";
    }

    // Vérifier nouveau mot de passe
    if ($nouveau_mdp !== $confirmation) {
        $erreurs[] = "Les mots de passe ne correspondent pas.";
    }

    if (empty($erreurs)) {
        $hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE clients SET email = ?, mot_de_passe = ? WHERE id = ?");
        $stmt->execute([$email, $hash, $client_id]);

        enregistrerAction($pdo, $client_id, "A modifié ses informations personnelles.");
        $_SESSION['user']['email'] = $email;

        header('Location: mon_espace.php');
        exit;
    }
}
?>

<h1>Modifier mes informations</h1>

<?php if ($erreurs): ?>
    <ul style="color:red;">
        <?php foreach ($erreurs as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST">
    <label>Email :</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>" required><br><br>

    <label>Mot de passe actuel :</label><br>
    <input type="password" name="mdp_actuel" required><br><br>

    <label>Nouveau mot de passe :</label><br>
    <input type="password" name="nouveau_mdp" required><br><br>

    <label>Confirmation :</label><br>
    <input type="password" name="confirmation" required><br><br>

    <button type="submit">Mettre à jour</button>
</form>
