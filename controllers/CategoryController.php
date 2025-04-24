function getCategoriesByBakery($bakery_id) {
   
    $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id IN (
        SELECT category_id FROM products WHERE bakery_id = ?
    )");
    $stmt->execute([$bakery_id]);
    
    return $stmt->fetchAll();
}

function getAllCategories() {
    $stmt = $this->pdo->query("SELECT * FROM categories");
    return $stmt->fetchAll();
}

