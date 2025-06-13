<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student Record Submission</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-primary text-white p-3">
  <nav class="navbar navbar-expand-lg navbar-dark container">
    <a class="navbar-brand" href="index.php">
      <img src="Assignment_one/img/img.png" alt="Logo" height="40">
    </a>
    <div>
      <a class="nav-link text-white" href="view.php">View Records</a>
    </div>
  </nav>
</header>
<div class="container mt-5">
<?php
require_once('crud.php');
require_once('validate.php');
$crud = new crud();
$valid = new validate();
if (isset($_POST['Submit'])) {
    $name = $crud->escape_string($_POST['name']);
    $email = $crud->escape_string($_POST['email']);
    $student_id = $crud->escape_string($_POST['student_id']);
    $course = $crud->escape_string($_POST['course']);
    $grade = $crud->escape_string($_POST['grade']);

    $msg = $valid->checkEmpty($_POST, array('name', 'email', 'student_id', 'course', 'grade'));
    $checkGrade = $valid->validGrade($grade);
    $checkEmail = $valid->validEmail($email);
    $checkCourse = $valid->validCourse($course);
    if ($msg != null) {
        echo "<div class='alert alert-danger'>$msg <a href='javascript:self.history.back();' class='btn btn-sm btn-outline-light ms-3'>Go Back</a></div>";
    } elseif (!$checkGrade) {
        echo "<div class='alert alert-danger'>Please enter a valid grade. <a href='javascript:self.history.back();' class='btn btn-sm btn-outline-light ms-3'>Go Back</a></div>";
    } elseif (!$checkEmail) {
        echo "<div class='alert alert-danger'>Please enter a valid email address. <a href='javascript:self.history.back();' class='btn btn-sm btn-outline-light ms-3'>Go Back</a></div>";
    } else {
        $result = $crud->execute("INSERT INTO students (name, email, student_id, course, grade) 
            VALUES('$name', '$email', '$student_id', '$course', '$grade')");
        echo "<div class='alert alert-success text-center'>
            Student record added successfully!
        </div>";
        echo "<div class='text-center mt-3'>
            <a href='index.php' class='btn btn-primary'>Add Another</a>
            <a href='view.php' class='btn btn-outline-secondary ms-2'>View All Records</a>
        </div>";
    }
}
?>
</div>
<footer class="bg-light text-center text-muted py-3 mt-5">
PHP Assignment by Prem
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
