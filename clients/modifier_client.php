<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

// Vérification que l'utilisateur est bien un client connecté
if (!isset($_SESSION['client_id'])) {
    header("Location: ../connexion.php");
    exit;
}

$client_id = $_SESSION['client_id'];
$message = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $nouveau_mdp = $_POST['nouveau_mdp'];
    $confirmation_mdp = $_POST['confirmation_mdp'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Adresse e-mail invalide.";
    } elseif (!empty($nouveau_mdp) && $nouveau_mdp !== $confirmation_mdp) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // Mise à jour de l'email
        $stmt = $pdo->prepare("UPDATE clients SET email = ? WHERE id = ?");
        $stmt->execute([$email, $client_id]);

        // Mise à jour du mot de passe si renseigné
        if (!empty($nouveau_mdp)) {
            $hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE clients SET mot_de_passe = ? WHERE id = ?");
            $stmt->execute([$hash, $client_id]);
        }

        enregistrer_action($client_id, "Modification des informations du client");
        $message = "Informations mises à jour avec succès.";
    }
}

// Récupérer les infos du client
$stmt = $pdo->prepare("SELECT email FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

?>

<h2>Modifier mes informations</h2>

<?php if ($message): ?>
    <p style="color: green;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post">
    <label>Adresse e-mail :</label>
    <input type="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required><br><br>

    <label>Nouveau mot de passe :</label>
    <input type="password" name="nouveau_mdp"><br><br>

    <label>Confirmer le mot de passe :</label>
    <input type="password" name="confirmation_mdp"><br><br>

    <button type="submit">Mettre à jour</button>
</form>
