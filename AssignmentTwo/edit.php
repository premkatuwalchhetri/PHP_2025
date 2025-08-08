<?php
$pageTitle = "Edit Product";
$pageDesc = "Edit product details and image in the admin panel.";
include 'inc/header.php';

// checking if user is logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';
$product = null;

// getting the product id from the url
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($productId <= 0) {
    header("Location: index.php");
    exit();
}

// getting the product from database
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

// handling the form submission
if ($_POST && $product) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];

    // validating the form data
    if (empty($name) || empty($description) || empty($price)) {
        $error = 'Please fill in all required fields.';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = 'Please enter a valid price.';
    } else {
        // handling the image upload
        $imageName = $product['image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = 'uploads/';

            // creating the uploadated directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageExtension, $allowedExtensions)) {
                // 5MB  is the limit
                if ($_FILES['image']['size'] <= 5000000) {
                    $newImageName = uniqid() . '.' . $imageExtension;
                    $uploadPath = $uploadDir . $newImageName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        // Delete old image if it exists
                        if ($product['image'] && file_exists($uploadDir . $product['image'])) {
                            unlink($uploadDir . $product['image']);
                        }
                        $imageName = $newImageName;
                    } else {
                        $error = 'Failed to upload image.';
                    }
                } else {
                    $error = 'Image size must be less than 5MB.';
                }
            } else {
                $error = 'Only JPG, JPEG, PNG, and GIF images are allowed.';
            }
        }

        // updating the product in database if there are no errors
        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
                $stmt->execute([$name, $description, $price, $imageName, $productId]);
                $success = 'Product updated successfully!';

                // refreshing the product data
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$productId]);
                $product = $stmt->fetch();
            } catch(PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>

// html and php for viewing the products
<div class="row">
    <div class="col-md-8">
        <h2>Edit Product</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?> <a href="index.php">View all products</a></div>
        <?php endif; ?>

        <?php if ($product): ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description: <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price ($): <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="price" name="price"
                           step="0.01" min="0" value="<?php echo $product['price']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Product Image:</label>
                    <?php if ($product['image']): ?>
                        <div class="mb-2">
                            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                 alt="Current image" class="img-thumbnail" style="max-width: 200px;">
                            <p class="small text-muted">current image</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <div class="form-text">less than 5mb is allowed</div>
                </div>

                <button type="submit" class="btn btn-primary">update Product</button>
                <a href="index.php" class="btn btn-secondary">cancel</a>
            </form>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>only admin</h5>
            </div>
            <div class="card-body">
                <p class="text-success"> You are logged in as an admin</p>
                <ul class="list-unstyled">
                    <li> modify product details</li>
                    <li> change product image</li>
                    <li> update price</li>
                </ul>
                <hr>
                <p class="small text-muted">only logged-in users can edit the products</p>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>
