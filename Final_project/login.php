<?php
session_start();

require_once 'inc/db.php';
require_once 'inc/user.php';
include 'inc/header.php';
$db = new Database();
$conn = $db->getConnection();
$user = new User($conn);
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $loggedInUser = $user->login($username, $password);

    if ($loggedInUser) {
        if (is_array($loggedInUser) && isset($loggedInUser['id'])) {
            $_SESSION['user_id'] = $loggedInUser['id'];
        } elseif (is_object($loggedInUser) && isset($loggedInUser->id)) {
            $_SESSION['user_id'] = $loggedInUser->id;
        } else {
            $_SESSION['user_id'] = $loggedInUser;
        }

        header("Location: content.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<h2>Login</h2>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>
<!-- form for the user -->
<form method="POST">
    <div class="mb-3">
        <label>Username:</label>
        <input type="text" name="username" class="form-control">
    </div>
    <div class="mb-3">
        <label>Password:</label>
        <input type="password" name="password" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<?php include 'inc/footer.php'; ?>
