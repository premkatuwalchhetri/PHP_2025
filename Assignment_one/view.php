<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>S,Records of students</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-primary text-white p-3 text-center">
    <h1>Student Records</h1>
    <nav>
        <a href="index.php" class="text-white mx-2">Add Student</a>
        <a href="view.php" class="text-white mx-2">View Records</a>
    </nav>
</header>

<div class="container mt-4">
    <h2>List of Students</h2>

    <table class="table table-bordered mt-3">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Grade</th>
            <th>Course</th>
        </tr>

        <?php
        include 'database.php';

        $sql = "SELECT * FROM week_four";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['grade'] . "</td>";
                echo "<td>" . $row['course'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No records found</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</div>

<footer class="bg-light text-center text-muted py-3 mt-5">
    PHP Assignment by Prem
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
