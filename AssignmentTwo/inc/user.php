<?php
session_start();

// Checking if the user is logged in or not
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
function loginUser($username, $password, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    return false;
}

// this is for registering the new users
function registerUser($username, $password, $pdo) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

// Logout the user
function logoutUser() {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

