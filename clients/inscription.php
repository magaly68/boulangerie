<?php
session_start();
require_once '../config/database.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Vérification de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Adresse e-mail invalide.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Mot de passe trop court (min. 6 caractères).";
    }

    if (empty($errors)) {
        $pdo = getPDO();

        // Vérifier si l'email est déjà utilisé
        $stmt = $pdo->prepare("SELECT id FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Email déjà utilisé.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO clients (email, mot_de_passe) VALUES (?, ?)");
            $stmt->execute([$email, $hashedPassword]);

            $_SESSION['client_id'] = $pdo->lastInsertId();
            header('Location: espace_client.php');
            exit;
        }
    }
}
?>

<!-- Formulaire d'inscription -->
<h2>Créer un compte client</h2>
<?php 
    foreach ($errors as $e): 
?>
    <p style="color:red"><?= htmlspecialchars($e) ?></p>
<?php 
    endforeach; 
?>
<form method="post">
    <label>Email : <input type="email" name="email" required></label><br>
    <label>Mot de passe : <input type="password" name="password" required></label><br>
    <button type="submit">S'inscrire</button>
</form>
