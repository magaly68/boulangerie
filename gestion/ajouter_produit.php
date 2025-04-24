<?php
require_once 'config/database.php';
$pdo = getPDO();

// Récupérer les catégories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libelle = $_POST['libelle'];
    $poids = $_POST['poids'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $categorie_id = $_POST['categorie_id'];

    $stmt = $pdo->prepare("INSERT INTO produits (libelle, poids, prix, description, categorie_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$libelle, $poids, $prix, $description, $categorie_id]);
    // Dans l'insertion :
    $stmt = $pdo->prepare("INSERT INTO produits (libelle, poids, prix, description, categorie_id, boulangerie_id) 
    VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$libelle, $poids, $prix, $description, $categorie_id, $boulangerie_id]);

    header("Location: liste_produits.php");
    exit;
}
?>

<h2>Ajouter un produit</h2>
<form method="post">
    <label>Libellé : <input type="text" name="libelle" required></label><br>
    <label>Poids : <input type="text" name="poids"></label><br>
    <label>Prix : <input type="number" step="0.01" name="prix" required></label><br>
    <label>Description : <textarea name="description"></textarea></label><br>
    <label>Catégorie :
        <select name="categorie_id">
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <button type="submit">Ajouter</button>
    enregistrer_action_gestionnaire($pdo, $_SESSION['gestionnaire_id'], "A ajouté le produit \"$nomProduit\"");

</form>
