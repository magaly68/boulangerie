// ==================== Connexion utilisateur ====================
function loginClient($pdo, $email, $password) { 
    
 }

function isClient() { 

 }

function isGestionnaire() { 

 }

// ==================== Produits ====================
function getProduitsByCategorie($pdo, $categorie_id) { 

 }

function getProduitsByBoulangerie($pdo, $boulangerie_id) { 

}

// ==================== Favoris ====================
function ajouterFavori($pdo, $client_id, $boulangerie_id) { 

}

function supprimerFavori($pdo, $client_id, $boulangerie_id) { 

}

function getFavorisClient($pdo, $client_id) { 
 $stmt = $pdo->prepare("SELECT b.id, b.nom FROM favoris f 
                           JOIN boulangeries b ON f.boulangerie_id = b.id
                           WHERE f.client_id = ?");
    $stmt->execute([$client_id]);
    return $stmt->fetchAll();

}

// ==================== Historique ====================
function enregistrerAction($pdo, $user_id, $action) { 

 }

function getHistoriqueClient($pdo, $client_id) { 
    $stmt = $pdo->prepare("SELECT * FROM historique WHERE utilisateur_id = ? ORDER BY date_action DESC LIMIT 20");
    $stmt->execute([$client_id]);
    return $stmt->fetchAll(); 
}


