<?php
$pageTitle = "Register";
$pageDesc  = "Welcome to our register page";

require "./inc/db.php";
require "./inc/user.php";
include 'inc/header.php';

$db = new Database();
$conn = $db->getConnection();
$user = new User($conn);

$error = '';
$success = '';
$imgDir = "img/";
if (!file_exists($imgDir)) {
    mkdir($imgDir, 0777, true);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $email    = $_POST['email'] ?? '';
    $image    = '';
    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $target = $imgDir . "/" . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $imageName;
        } else {
            $error = "Image upload failed.";
        }
    }
    if (!$username || !$password || !$email) {
        $error = "All fields are required.";
    }
    if (empty($error)) {
        $result = $user->register($username, $password, $email, $image);

        if ($result['success']) {
            $success = "Registration successful! You can now <a href='login.php'>login</a>.";
        } else {
            $error = $result['error'];
        }
    }
}
?>
<h2>Register</h2>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Username:</label>
        <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label>Email:</label>
        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label>Password:</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Upload Image:</label>
        <input type="file" name="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Register</button>
</form>

<?php include 'inc/footer.php'; ?>


