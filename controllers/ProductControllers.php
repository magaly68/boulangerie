function createProduct($data) {
    // validation des données
    $stmt =$this->pdo->prepare(INSERT INTO produits (nom, poids, image, prix, description, mise_à_jour, categorie_id )) 
    VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";
    $stmt->execute([
        $data['nom'],
        $data['poids'],
        $data['image'],
        $data['prix'],
        $data['description'],
        $data['categorie_id']
        $data
    ]);
}

function updateProduct($data) {
    // Assure-toi que le gestionnaire a bien les droits
    $stmt = $this->pdo->prepare("INSERT INTO products (name, weight, image_path, price, description, updated_at, category_id, bakery_id)
    VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)");
    $stmt->execute([
        $data['nom'],
        $data['poids'],
        $data['image'],
        $data['prix'],
        $data['description'],
        $data['categorie_id'],
        $data['boulangerie_id']
    ]);
}

function deleteProduct($data) {
    // Assure-toi que le gestionnaire a bien les droits
    $stmt = $this->pdo->prepare("INSERT INTO products (name, weight, image_path, price, description, updated_at, category_id, bakery_id)
    VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)");
    $stmt->execute([
        $data['nom'],
        $data['poids'],
        $data['image'],
        $data['prix'],
        $data['description'],
        $data['categorie_id'],
        $data['boulangerie_id']
    ]);
}

