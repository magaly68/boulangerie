<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['client_id'])) {
    header('Location: connexion.php');
    exit;
}

$pdo = getPDO();
$client_id = $_SESSION['client_id'];
$errors = [];
$success = "";

// Récupération des infos actuelles
$stmt = $pdo->prepare("SELECT email FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mise à jour de l'email
    if (!empty($_POST['email'])) {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Adresse e-mail invalide.";
        } else {
            $stmt = $pdo->prepare("UPDATE clients SET email = ? WHERE id = ?");
            $stmt->execute([$email, $client_id]);
            $success = "Adresse e-mail mise à jour !";
        }
    }

    // Mise à jour du mot de passe
    if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        if ($_POST['new_password'] !== $_POST['confirm_password']) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        } elseif (strlen($_POST['new_password']) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
        } else {
            $hashedPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE clients SET mot_de_passe = ? WHERE id = ?");
            $stmt->execute([$hashedPassword, $client_id]);
            $success = "Mot de passe mis à jour !";
        }
    }
}
?>

<h2>Modifier votre profil</h2>

<?php foreach ($errors as $e): ?>
    <p style="color: red"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<?php if ($success): ?>
    <p style="color: green"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="post">
    <label>Email :
        <input type="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>
    </label><br><br>

    <h4>Changer le mot de passe (facultatif)</h4>
    <label>Nouveau mot de passe :
        <input type="password" name="new_password">
    </label><br>
    <label>Confirmer le mot de passe :
        <input type="password" name="confirm_password">
    </label><br><br>

    <button type="submit">Mettre à jour</button>
</form>
