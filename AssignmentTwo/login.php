<?php
$pageTitle = "Edit Product";
$pageDesc = "Edit product details and image in the admin panel.";
require_once "./inc/db.php";
require_once "./inc/user.php";
require './inc/header.php';

$error = '';
$success = '';

// this handles the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // login form submission
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (!$username || !$password) {
            $error = 'Please fill in all fields.';
        } elseif (loginUser($username, $password, $pdo)) {
            header("Location: index.php");
            exit();
        } else {
            $error = 'Invalid username or password.';
        }
    }

    // registration form submission
    if (isset($_POST['register'])) {
        $username = trim($_POST['reg_username']);
        $password = $_POST['reg_password'];
        $confirm = $_POST['confirm_password'];

        if (!$username || !$password || !$confirm) {
            $error = 'Please fill in all fields.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters long.';
        } elseif (registerUser($username, $password, $pdo)) {
            $success = 'Account created successfully! You can now login.';
        } else {
            $error = 'Username already exists or registration failed.';
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2>Login</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <!-- this is login form -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Login to Your Account</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>

        <!-- this si registration form for the new users -->
        <div class="card">
            <div class="card-header">
                <h4>Create New Account</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="reg_username" class="form-label">Username:</label>
                        <input type="text" class="form-control" id="reg_username" name="reg_username" required>
                    </div>

                    <div class="mb-3">
                        <label for="reg_password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="reg_password" name="reg_password">
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>

                    <button type="submit" name="register" class="btn btn-success">create new account</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>
