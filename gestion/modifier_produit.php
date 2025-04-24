<?php
require_once 'config/database.php';
$pdo = getPDO();

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();

$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ? AND boulangerie_id = ?");
$stmt->execute([$id, $boulangerie_id]);
$produit = $stmt->fetch();

if (!$produit) {
    die("Accès non autorisé.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libelle = $_POST['libelle'];
    $poids = $_POST['poids'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $categorie_id = $_POST['categorie_id'];

    $stmt = $pdo->prepare("UPDATE produits SET libelle=?, poids=?, prix=?, description=?, categorie_id=?, date_mise_a_jour=NOW() WHERE id=?");
    $stmt->execute([$libelle, $poids, $prix, $description, $categorie_id, $id]);

    header("Location: liste_produits.php");
    exit;
}
?>

<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['gestionnaire_id'])) {
    header("Location: connexion.php");
    exit;
}

$pdo = getPDO();
$boulangerie_id = $_SESSION['boulangerie_id'];
$produit_id = $_GET['id'] ?? null;

if (!$produit_id) {
    header("Location: liste_produits.php");
    exit;
}

// Récupérer le produit
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ? AND boulangerie_id = ?");
$stmt->execute([$produit_id, $boulangerie_id]);
$produit = $stmt->fetch();

if (!$produit) {
    echo "Produit introuvable ou non autorisé.";
    exit;
}

// Récupérer les catégories
$stmt = $pdo->query("SELECT id, nom FROM categories");
$categories = $stmt->fetchAll();

$erreur = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libelle = $_POST['libelle'];
    $categorie_id = $_POST['categorie_id'];
    $prix = $_POST['prix'];
    $poids = $_POST['poids'];
    $description = $_POST['description'] ?? null;

    // Mise à jour de la photo si une nouvelle est envoyée
    $photo = $produit['photo'];
    if (!empty($_FILES['photo']['name'])) {
        $dossier = '../uploads/';
        if (!is_dir($dossier)) mkdir($dossier, 0777, true);

        $fichier = basename($_FILES['photo']['name']);
        $chemin_photo = $dossier . time() . "_" . $fichier;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $chemin_photo)) {
            $photo = str_replace('../', '', $chemin_photo);
        } else {
            $erreur = "Erreur lors de l’upload de la photo.";
        }
    }

    if (!$erreur) {
        $stmt = $pdo->prepare("
            UPDATE produits 
            SET libelle = ?, prix = ?, poids = ?, description = ?, photo = ?, categorie_id = ?, date_mise_a_jour = NOW()
            WHERE id = ? AND boulangerie_id = ?
        ");
        $stmt->execute([$libelle, $prix, $poids, $description, $photo, $categorie_id, $produit_id, $boulangerie_id]);

        $success = "Produit mis à jour avec succès.";
        // recharger les données
        $produit = array_merge($produit, $_POST, ['photo' => $photo]);
    }
}
?>

<h2>Modifier le produit</h2>

<?php if ($erreur): ?>
    <p style="color:red"><?= $erreur ?></p>
<?php elseif ($success): ?>
    <p style="color:green"><?= $success ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>Libellé :</label><br>
    <input type="text" name="libelle" value="<?= htmlspecialchars($produit['libelle']) ?>" required><br><br>

    <label>Catégorie :</label><br>
    <select name="categorie_id">
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $produit['categorie_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Prix (€) :</label><br>
    <input type="number" step="0.01" name="prix" value="<?= $produit['prix'] ?>" required><br><br>

    <label>Poids (g) :</label><br>
    <input type="number" name="poids" value="<?= $produit['poids'] ?>" required><br><br>

    <label>Description :</label><br>
    <textarea name="description"><?= htmlspecialchars($produit['description']) ?></textarea><br><br>

    <label>Photo actuelle :</label><br>
    <?php if ($produit['photo']): ?>
        <img src="../<?= $produit['photo'] ?>" alt="photo" width="100"><br>
    <?php else: ?>
        <p>Aucune photo</p>
    <?php endif; ?>
    <label>Changer la photo :</label><br>
    <input type="file" name="photo" accept="image/*"><br><br>

    <button type="submit">Enregistrer</button>
</form>

<p><a href="liste_produits.php">← Retour à la liste</a></p>


<h2>Modifier un produit</h2>
<form method="post">
    <label>Libellé : <input type="text" name="libelle" value="<?= $produit['libelle'] ?>" required></label><br>
    <label>Poids : <input type="text" name="poids" value="<?= $produit['poids'] ?>"></label><br>
    <label>Prix : <input type="number" step="0.01" name="prix" value="<?= $produit['prix'] ?>" required></label><br>
    <label>Description : <textarea name="description"><?= $produit['description'] ?></textarea></label><br>
    <label>Catégorie :
        <select name="categorie_id">
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $produit['categorie_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <button type="submit">Modifier</button>
</form>
enregistrer_action_gestionnaire($pdo, $_SESSION['gestionnaire_id'], "A ajouté le produit \"$nomProduit\"");

