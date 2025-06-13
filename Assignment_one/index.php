<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Records of students</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Student Portal</a>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">

        <h2 class="mb-4 text-center">Add New Student</h2>

        <form action="add.php" method="POST">
          <div class="mb-3">
            <label for="studentName" class="form-label">Name</label>
            <input type="text" class="form-control" id="studentName" name="name" placeholder="enter your name" required>
          </div>
          <div class="mb-3">
            <label for="studentEmail" class="form-label">Email address</label>
            <input type="email" class="form-control" id="studentEmail" name="email" placeholder="enter your email" required>
          </div>
          <div class="mb-3">
            <label for="studentID" class="form-label">Student ID</label>
            <input type="text" class="form-control" id="studentID" name="student_id" placeholder="enter your student id" required>
          </div>
          <div class="mb-3">
            <label for="course" class="form-label">Course</label>
            <input type="text" class="form-control" id="course" name="course" placeholder="enter your course" required>
          </div>
          <div class="mb-3">
            <label for="grade" class="form-label">Grade</label>
            <input type="text" class="form-control" id="grade" name="grade" placeholder="enter your grade" required>
          </div>
          <div class="d-grid">
            <button type="submit" name="Submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <footer class="bg-light text-center text-muted py-3 mt-5">
    PHP Assignment by Prem
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
