<?php
session_start();
include 'inc/db.php';
include 'inc/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$db = new Database();
$conn = $db->getConnection();

$imgDir = "img/";
if (!is_dir($imgDir)) {
    mkdir($imgDir, 0777, true);
}
if (isset($_GET['delete_user'])) {
    $deleteId = (int)$_GET['delete_user'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$deleteId]);
    header('Location: admin.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = (int)$_POST['id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadedName = basename($_FILES['image']['name']);
        $targetFile = $imgDir . $uploadedName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imageName = $uploadedName;
        }
    }
    if ($imageName) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, image = ? WHERE id = ?");
        $stmt->execute([$username, $email, $imageName, $id]);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $id]);
    }
    header('Location: admin.php');
    exit;
}
$users = $conn->query("SELECT * FROM users")->fetchAll();
?>

<h2>Admin Panel - Manage Users</h2>

<!-- table for the admin panel to show the registered user -->
<table class="table table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Username</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td>
                <?php if (!empty($user['image']) && file_exists($imgDir . $user['image'])): ?>
                    <img src="<?= htmlspecialchars($imgDir . $user['image']) ?>" alt="User Image" width="60" height="60">
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>
            <td>
                <form method="POST" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="form-control form-control-sm" style="max-width: 130px;">
            </td>
            <td>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control form-control-sm" style="max-width: 200px;">
            </td>
            <td>
                <input type="file" name="image" accept="image/*" class="form-control form-control-sm" style="max-width: 140px;">
                <button type="submit" name="update_user" class="btn btn-primary btn-sm ms-2">Update</button>
                <a href="?delete_user=<?= htmlspecialchars($user['id']) ?>" onclick="return confirm('Delete this user?')" class="btn btn-danger btn-sm ms-2">Delete</a>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include 'inc/footer.php'; ?>
