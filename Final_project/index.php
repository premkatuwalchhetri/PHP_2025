<?php
$pageTitle = "Home";
$pageDesc  = "Welcome to our simple blog website.";
require './inc/header.php';
?>
<section class="lesson-masthead">
    <h1>Welcome to the Website</h1>
</section>

<section class="welcomeMessage">
    <p>This is a simple blogsite where you can register, log in, and manage content</p>
</section>

<section class="login-register">
    <div class="login">
        <a href="login.php" class="btn btn-primary">Login</a>
    </div>
    <div class="register">
        <a href="register.php" class="btn btn-success">Register</a>
    </div>
</section>
<?php require './inc/footer.php'; ?>
