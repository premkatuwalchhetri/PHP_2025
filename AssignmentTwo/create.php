<?php
include './inc/header.php';

$error = '';
$success = '';

// this is for handling the form submission
if ($_POST) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];

    // this validate the form data
    if (empty($name) || empty($description) || empty($price)) {
        $error = 'Please fill in all the required fields';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = 'enter a valid price';
    } else {
        // this is for handling image uploading
        $imageName = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = 'img/';

            // it creates the uploads directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 755, true);
            }

            $imageExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageExtension, $allowedExtensions)) {
                if ($_FILES['image']['size'] <= 5000000) { // 5MB limit
                    $imageName = uniqid() . '.' . $imageExtension;
                    $uploadPath = $uploadDir . $imageName;

                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        $error = 'cannot upload image at the moment';
                    }
                } else {
                    $error = 'image size must be less than 5MB';
                }
            } else {
                $error = 'Only JPG, JPEG, PNG, and GIF images are allowed here';
            }
        }

        // this inserts the product into database if there are no errors
        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $description, $price, $imageName]);
                $success = 'Product added successfully!';

                // this clears the form data
                $name = $description = $price = '';
            } catch(PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>

<div class="row">
    <div class="col-md-8">
        <h2>Add New Product</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?> <a href="index.php">View all products</a></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name"
                       value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description: <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price ($): <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="price" name="price"
                       step="0.01" min="0" value="<?php echo isset($price) ? $price : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Product Image:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <div class="form-text">Optional. Max file size: 5MB. Allowed: JPG, JPEG, PNG, GIF</div>
            </div>

            <button type="submit" class="btn btn-success">Add Product</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>
