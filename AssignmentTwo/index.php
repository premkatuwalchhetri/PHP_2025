<?php
$pageTitle = "This is the index page";
$pageDesc  = "product management dashboard";
require 'inc/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <p class="lead">Welcome to our product management system</p>

        <?php if (isLoggedIn()): ?>
            <div class="alert alert-success">
                You are logged in as an admin. You can edit and delete products.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h2>All Products</h2>
        <a href="create.php" class="btn btn-success mb-3">Add New Product</a>

        <div class="row">
            <?php
            $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
            $products = $stmt->fetchAll();

            if ($products):
                foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <?php if (!empty($product['image'])): ?>
                                <img src="img/<?= htmlspecialchars($product['image']) ?>"
                                     class="card-img-top product-image"
                                     alt="<?= htmlspecialchars($product['name']) ?>">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <span class="text-muted">No Image</span>
                                </div>
                            <?php endif; ?>

                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                                <p class="card-text"><strong>$<?= number_format($product['price'], 2) ?></strong></p>

                                <?php if (isLoggedIn()): ?>
                                    <a href="edit.php?id=<?= $product['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="delete.php?id=<?= $product['id'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach;
            else: ?>
                <div class="col-md-12">
                    <div class="alert alert-info">
                        No products found <a href="create.php">add the first product</a>!
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>
