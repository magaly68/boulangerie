<?php
session_start();
require_once '../config/database.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Adresse e-mail invalide.";
    }

    if (empty($errors)) {
        $pdo = getPDO();

        $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        $client = $stmt->fetch();

        if ($client && password_verify($password, $client['mot_de_passe'])) {
            $_SESSION['client_id'] = $client['id'];
            header("Location: espace_client.php");
            exit;
        } else {
            $errors[] = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!-- Formulaire de connexion -->
<h2>Connexion client</h2>
<?php 
foreach ($errors as $e): 
?>
    <p style="color:red">
        <?= htmlspecialchars($e) ?>
    </p>
<?php 
endforeach; 
?>
<form method="post">
    <label>Email : <input type="email" name="email" required></label><br>
    <label>Mot de passe : <input type="password" name="password" required></label><br>
    <button type="submit">Se connecter</button>
</form>
