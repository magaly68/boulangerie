function getFavoriteBakeries($user_id) {
    $stmt = $this->pdo->prepare("SELECT b.* FROM favorites f JOIN bakeries b ON f.bakery_id = b.id WHERE f.user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}
 if user connected
function getUserId() {
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    }
    return null;
    
}
function getUserRole() {
    if (isset($_SESSION['user_role'])) {
        return $_SESSION['user_role'];
    }
    return null;
}
function isUserConnected() {
    return isset($_SESSION['user_id']);
}

filter_var($email, FILTER_VALIDATE_EMAIL) !== false
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    return preg_match($pattern, $email) === 1;
}
function isValidPassword($password) {
    return strlen($password) >= 8;
}
function isValidName($name) {
    return preg_match('/^[a-zA-Z\s]+$/', $name);
}
