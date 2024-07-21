<?php
session_start();

include "./database/db.php";

// Assuming the user ID is stored in the session
$user_id = $_SESSION['userid'];

// Fetch the user information from the database
$sql = "SELECT * FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();
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
            <h3 class="text-themecolor">Profile</h3>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="javascript:void(0)">Home</a>
              </li>
              <li class="breadcrumb-item active">Profile</li>
            </ol>
          </div>
        </div>

        <div class="row">
          <!-- Column -->
          <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
              <div class="card-body">
                <center class="mt-4">
                  <img src="https://t4.ftcdn.net/jpg/08/39/11/15/240_F_839111502_nVLKHc9EgIfNoxBtd1netbEKkjbfbLfy.jpg" class="img-circle" width="250" />
                  <h4 class="card-title mt-2"><?php echo $user['username']; ?></h4>
                  <div class="row text-center justify-content-md-center">
                    <div class="col text-right">
                      <a href="../logout.php"><button type="submit" class="btn btn-danger">LogOut</button></a>
                    </div>
                  </div>
                </center>
              </div>
            </div>
          </div>

          <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
              <div class="card-body">
                <form class="row g-3" action="" method="post">
                  <div class="col-md-6">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-field">
                      <?php echo $user['username']; ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="type" class="form-label">Account Type</label>
                    <div class="input-field">
                      <?php echo $user['type']; ?>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- Column -->
        </div>

      </div>

    </div>

  </div>

</body>
</html>
