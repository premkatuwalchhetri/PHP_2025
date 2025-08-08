<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require './inc/db.php';
require './inc/user.php';

if (isset($_GET['id'])) {
    $db = (new Database())->getConnection();
    $user = new User($db);
    $user->delete($_GET['id']);
}

header("Location: index.php");
exit;
?>

