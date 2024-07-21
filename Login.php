<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iqlaascollection";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['type'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ? AND type = ?");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['type'] = $row['type'];

        // Update user status to online
        $update_stmt = $conn->prepare("UPDATE users SET status = 'online', last_login = NOW() WHERE userid = ?");
        $update_stmt->bind_param("i", $row['userid']);
        $update_stmt->execute();

        $_SESSION['login_status'] = "success";

        if ($_SESSION['type'] == 'Admin') {
            header("Location: ./admin/Dashboard.php");
            exit();
        } elseif ($_SESSION['type'] == 'Cashier') {
            header("Location: ./cashier/order.php");
            exit();
        } else {
            $_SESSION['login_status'] = "unauthorized";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['login_status'] = "invalid";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-color: #343a40;
    }
    .card {
      max-width: 800px;
      margin: auto;
      background-color: #f8f9fa;
      min-height: 470px; 
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .form-control, .form-select {
      height: calc(2.875rem + 2px); 
      font-size: 1.25rem; 
    }
    .btn {
      font-size: 1.25rem; 
    }
    .btn-wide {
      width: 100%; 
    }
  </style>
  <script>
    function validateForm() {
        var username = document.getElementById("username").value;
        var regex = /^[A-Za-z]+$/;

        if (!regex.test(username)) {
            alert("Username must contain only letters.");
            return false;
        }

        return true;
    }

    window.onload = function() {
        <?php
        if (isset($_SESSION['login_status'])) {
            if ($_SESSION['login_status'] == "success") {
                echo 'alert("Login successful!");';
                unset($_SESSION['login_status']);
            } elseif ($_SESSION['login_status'] == "invalid") {
                echo 'alert("Invalid username or password.");';
                unset($_SESSION['login_status']);
            } elseif ($_SESSION['login_status'] == "unauthorized") {
                echo 'alert("Unauthorized role.");';
                unset($_SESSION['login_status']);
            }
        }
        ?>
    };
  </script>
</head>
<body>

<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-primary text-white">
      <h2>Login Form</h2>
    </div>
    <div class="card-body">
      <form method="POST" onsubmit="return validateForm()">
        <div class="mb-3 mt-3">
          <label for="username" class="form-label">Username:</label>
          <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password:</label>
          <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
        </div>
        <div class="mb-3">
          <label for="type" class="form-label">Account Type:</label>
          <select name="type" id="type" class="form-select" required>
            <option value="Admin">Admin</option>
            <option value="Cashier">Cashier</option>
          </select>
        </div>
        <button type="submit" name="submit" class="btn btn-primary btn-lg btn-wide">Login</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
