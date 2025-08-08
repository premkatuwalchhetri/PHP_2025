<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require './inc/header.php';
require "./inc/db.php";
require "./inc/user.php";
$db = (new Database())->getConnection();
$user = new User($db);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $found = $user->find($id);
    if (!$found) {
        header("Location: dashboard.php");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    if ($user->update($id, $username)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Update failed.";
    }
}

require './templates/header.php';
?>
<section class="lesson-masthead">
    <h1>Update User</h1>
</section>
<section class="errorMessageRow">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
</section>
<section class="loginFormRow">
    <form method="POST" class="w-50">
        <div class="mb-3">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($found['username']) ?>" required>
        </div>
        <button class="btn btn-primary" type="submit">Update</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</section>
<?php require './templates/footer.php'; ?>

