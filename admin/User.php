<?php
include "./database/db.php";

$query = "SELECT userid, username, password, type, date, status, last_login FROM users";

if(isset($_GET['search'])){
    $search = sanitizeInput($_GET['search']);
    $query .= " WHERE type LIKE '%$search%'";
}

$result = $conn->query($query);

if (isset($_GET["deleted_id"])) {
    $deleted_id = sanitizeInput($_GET["deleted_id"]);
    $deleteQuery = "DELETE FROM users WHERE userid = $deleted_id";
    if ($conn->query($deleteQuery)) {
        echo "<script>alert('User has been deleted successfully');</script>";
        header("Location: User.php");
        exit(); 
    } else {
        echo "<script>alert('Failed to delete user');</script>";
    }
}

function sanitizeInput($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input)));
}
?>


<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>

<body class="fix-header card-no-border fix-sidebar">

<div id="main-wrapper">

<?php include "topnavbar.php"; ?>
<?php include "sidebar.php"; ?>

<div class="page-wrapper">
  <div class="container-fluid">
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">Users Table</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0)">Home</a>
          </li>
          <li class="breadcrumb-item active">Table Users</li>
        </ol>
      </div>
      <div class="col-md-7 align-self-center">
        <a href="UserForms.php" class="btn waves-effect waves-light btn btn-info pull-right hidden-sm-down text-white">Add Users</a>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Users Table</h4>
            <h6 class="card-subtitle">Add Users <code>.table</code></h6>

            <form action="User.php" method="GET" class="mb-4">
              <div class="form-group">
                  <input type="text" name="search" class="form-control" placeholder="Search by Account Type">
              </div>
              <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <div class="container mt-3">
              <hr class="my-4">

              <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                  <thead>
                    <tr>
                      <th>UserID</th>
                      <th>Username</th>
                      <th>Password</th>
                      <th>Account Type</th>
                      <th>Date</th>
                      <th>Last Login</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["userid"] . "</td>";
                            echo "<td>" . $row["username"] . "</td>";
                            echo "<td>" . $row["password"] . "</td>";
                            echo "<td>" . $row["type"] . "</td>";
                            echo "<td>" . $row["date"] . "</td>";
                            echo "<td>" . ($row["last_login"] ? $row["last_login"] : 'Never') . "</td>";
                            echo "<td>" . ($row["status"] == 'online' ? '<span class="badge badge-success">Online</span>' : '<span class="badge badge-secondary">Offline</span>') . "</td>";
                            echo "<td><a href='UserForms.php?id=" . $row["userid"]. "' onclick='return confirm(\"Do you want to update this user?\")' class='btn btn-warning'><i class='fa fa-edit'></i></a> &nbsp;";
                            echo "<a href='User.php?deleted_id=" . $row["userid"]. "' onclick='return confirm(\"Are you sure you want to delete this user?\")' class='btn btn-danger'><i class='fa fa-trash'></i></a> &nbsp;";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No records found</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</div>
</div>

</body>
</html>
