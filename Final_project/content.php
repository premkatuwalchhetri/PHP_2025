<?php
session_start();

include 'inc/db.php';
include 'inc/header.php';

$db = new Database();
$conn = $db->getConnection();

// Create table with image filename column
$conn->exec("CREATE TABLE IF NOT EXISTS content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    body TEXT,
    image VARCHAR(255) DEFAULT NULL
)");

$message = '';
$loggedIn = false;
if (isset($_SESSION['user_id'])) {
    $loggedIn = true;
}

$imgDir = 'img/';
if (!is_dir($imgDir)) {
    mkdir($imgDir, 0777, true);
}

if ($loggedIn && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $title = $_POST['title'];
    $body = $_POST['body'];

    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $target = $imgDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $message = "Failed to upload image.";
            $imageName = null;
        }
    }

    $stmt = $conn->prepare("INSERT INTO content (title, body, image) VALUES (?, ?, ?)");
    $stmt->execute([$title, $body, $imageName]);
    if (!$message) {
        $message = "Content added.";
    }
}

if ($loggedIn && isset($_GET['delete'])) {
    // Delete image file from server before deleting DB record
    $stmt = $conn->prepare("SELECT image FROM content WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $contentToDelete = $stmt->fetch();
    if ($contentToDelete && $contentToDelete['image'] && file_exists($imgDir . $contentToDelete['image'])) {
        unlink($imgDir . $contentToDelete['image']);
    }

    $stmt = $conn->prepare("DELETE FROM content WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: content.php");
    exit;
}

if ($loggedIn && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $body = $_POST['body'];

    // Get current image filename
    $stmt = $conn->prepare("SELECT image FROM content WHERE id = ?");
    $stmt->execute([$id]);
    $currentContent = $stmt->fetch();

    $imageName = $currentContent['image'];

    if (!empty($_FILES['image']['name'])) {
        $newImageName = time() . '_' . basename($_FILES['image']['name']);
        $target = $imgDir . $newImageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // Delete old image if exists
            if ($imageName && file_exists($imgDir . $imageName)) {
                unlink($imgDir . $imageName);
            }
            $imageName = $newImageName;
        }
    }

    $stmt = $conn->prepare("UPDATE content SET title = ?, body = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $body, $imageName, $id]);
    $message = "Content updated.";
}

$contents = $conn->query("SELECT * FROM content")->fetchAll();
?>

<h2>Content</h2>
<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if ($loggedIn): ?>
    <h4>Add New Content</h4>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" class="form-control mb-2" placeholder="Title" required>
        <textarea name="body" class="form-control mb-2" placeholder="Body" required></textarea>
        <input type="file" name="image" class="form-control mb-2" accept="image/*">
        <button type="submit" name="add" class="btn btn-success">Add</button>
    </form>
    <hr>
<?php endif; ?>

<h4>Existing Content</h4>
<?php foreach ($contents as $c): ?>
    <?php if ($loggedIn): ?>
        <form method="POST" enctype="multipart/form-data" class="mb-3">
            <input type="hidden" name="id" value="<?= $c['id'] ?>">
            <input type="text" name="title" value="<?= htmlspecialchars($c['title']) ?>" class="form-control mb-1" required>
            <textarea name="body" class="form-control mb-1" required><?= htmlspecialchars($c['body']) ?></textarea>

            <?php if ($c['image']): ?>
                <img src="img/<?= htmlspecialchars($c['image']) ?>" alt="Image" style="max-width: 150px; display: block; margin-bottom: 8px;">
            <?php endif; ?>

            <input type="file" name="image" class="form-control mb-2" accept="image/*">

            <button type="submit" name="update" class="btn btn-primary btn-sm">Update</button>
            <a href="?delete=<?= $c['id'] ?>" onclick="return confirm('Delete this content?')" class="btn btn-danger btn-sm">Delete</a>
        </form>
    <?php else: ?>
        <div class="card mb-3 p-2">
            <h5><?= htmlspecialchars($c['title']) ?></h5>
            <?php if ($c['image']): ?>
                <img src="img/<?= htmlspecialchars($c['image']) ?>" alt="Image" style="max-width: 300px; margin-bottom: 10px;">
            <?php endif; ?>
            <p><?= nl2br(htmlspecialchars($c['body'])) ?></p>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<?php include 'inc/footer.php'; ?>
