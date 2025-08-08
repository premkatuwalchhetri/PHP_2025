<?php
require './inc/header.php';

// checking if user is logged in or not
if (!isLoggedIn()) {
header("Location: login.php");
exit();
}

$error = '';
$success = '';

// getting product id from the url
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($productId <= 0) {
header("Location: index.php");
exit();
}

// getting product from the database
try {
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
header("Location: index.php");
exit();
}
} catch(PDOException $e) {
$error = 'Database error: ' . $e->getMessage();
}

// this handles the deletion
if (isset($_POST['confirm_delete']) && $product) {
try {
// deleting the product from database
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$productId]);

// deleting the image file if it exists
if ($product['image'] && file_exists('uploads/' . $product['image'])) {
unlink('uploads/' . $product['image']);
}

header("Location: index.php?deleted=1");
exit();
} catch(PDOException $e) {
$error = 'Failed to delete product: ' . $e->getMessage();
}
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2>Delete Product</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($product): ?>
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4> confirm delete</h4>
                </div>
                <div class="card-body">
                    <p>Are you sure you want to delete this product? This action cannot be undone.</p>

                    <div class="product-preview border p-3 mb-3">
                        <?php if ($product['image']): ?>
                            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                 alt="Product image" class="img-thumbnail mb-2" style="max-width: 150px;">
                        <?php endif; ?>
                        <h5><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p><strong>Price: $<?php echo number_format($product['price'], 2); ?></strong></p>
                    </div>

                    <form method="POST" action="">
                        <button type="submit" name="confirm_delete" class="btn btn-danger">
                            Yes, Delete Product
                        </button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'inc/footer.php'; ?>

