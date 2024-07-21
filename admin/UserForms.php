<?php

include "./database/db.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    if ($count == 1) {
        $stmt = $conn->prepare("UPDATE users SET status='online' WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        echo json_encode("Login Success");
    } else {
        echo json_encode("Login Error");
    }

    $stmt->close();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE userid = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $username = $row['username'];
        $password = $row['password'];
        $type = $row['type'];
    } else {
        echo "User with ID $id not found";
    }

    $stmt->close();
}

if (isset($_POST['users'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type'];

    if ($username == "" || $password == "" || $type == "") {
        $info = "<font color='red'>Please fill all required fields</font>";
    } else {
        if (isset($_POST['id']) && $_POST['id'] != "") {
            
            $id = $_POST['id'];
            $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, type = ? WHERE userid = ?");
            $stmt->bind_param("sssi", $username, $password, $type, $id);

            if ($stmt->execute()) {
                echo "Record updated successfully";
                header("Location: User.php");
                exit();
            } else {
                echo "Error updating record: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, password, type) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $type);

            if ($stmt->execute()) {
                echo "New record created successfully";
                header("Location: User.php");
                exit();
            } else {
                echo "Error inserting record: " . $stmt->error;
            }

            $stmt->close();
        }
    }
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
                            <h3 class="text-themecolor">Users Form</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active">Form Users</li>
                            </ol>
                        </div>
                        <div class="col-md-7 align-self-center">
                            <a href="User.php" class="btn waves-effect waves-light btn btn-info pull-right hidden-sm-down text-white">Review Table</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Users Form</h4>
                                    <h6 class="card-subtitle">Add Users <code>.Form</code></h6>

                                    <div class="container">
                                        <div id="info"><?php if (isset($info)) echo $info; ?></div>
                                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                            <input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Enter username" id="username" name="username" required value="<?php if (isset($username)) echo $username; ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Enter password" id="password" name="password" required value="<?php if (isset($password)) echo $password; ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="type" class="form-label">Account Type</label>
                                                <div class="input-field">
                                                    <input type="text" class="form-control" placeholder="Enter type" id="type" name="type" required value="<?php if (isset($type)) echo $type; ?>">
                                                </div>
                                            </div>
                                            <div class="col text-right">
                                                <input type="submit" id="users" name="users" class="btn btn-success btn-lg btn-fw" value="<?php if (isset($_GET["id"])) echo "Edit"; else echo "Save" ?>">
                                            </div>
                                        </form>
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
