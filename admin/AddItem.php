<?php
include "./database/db.php";

if(isset($_GET['search'])){
    $search = $_GET['search'];
    $query = "SELECT * FROM additem WHERE itemname LIKE '%$search%'";
} else {
    $query = "SELECT * FROM additem";
}

$result = $conn->query($query);

if (isset($_GET["deleted_id"])) {
    $deleted_id = $_GET["deleted_id"];
    $stmt = $conn->prepare("DELETE FROM additem WHERE id = ?");
    $stmt->bind_param("i", $deleted_id);

    if ($stmt->execute()) {
        header("Location: AddItem.php?message=deleted");
    } else {
        header("Location: AddItem.php?message=error");
    }
    $stmt->close();
    exit();
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
                            <h3 class="text-themecolor">AddItem Table</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active">Table AddItem</li>
                            </ol>
                        </div>
                        <div class="col-md-7 align-self-center">
                            <a href="AddItemForm.php" class="btn waves-effect waves-light btn btn-info pull-right hidden-sm-down text-white">Add Item</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">AddItem Table</h4>
                                    <h6 class="card-subtitle">Add AddItem <code>.table</code></h6>

                                    <form action="AddItem.php" method="GET" class="mb-4">
                                        <div class="form-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search by Item Name">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </form>

                                    <div class="container mt-3">
                                        <hr class="my-4">
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover text-center">
                                                <thead>
                                                    <tr>
                                                        <th>ItemID</th>
                                                        <th>ItemName</th>
                                                        <th>Description</th>
                                                        <th>Quantity</th>
                                                        <th>Price</th>
                                                        <th>Image</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>";
                                                            echo "<td>" . $row["id"] . "</td>";
                                                            echo "<td>" . $row["itemname"] . "</td>";
                                                            echo "<td>" . $row["des"] . "</td>";
                                                            echo "<td>" . $row["qty"] . "</td>";
                                                            echo "<td>" . $row["price"] . "</td>";
                                                            echo "<td><img src='" . $row["file"] . "' alt='Item Image' style='width:100px; height:auto;'/></td>";
                                                            echo "<td>" . $row["date"] . "</td>";
                                                            echo "<td><a href='AddItemForm.php?id=" . $row["id"]. "' class='btn btn-info' onclick='return confirm(\"Are you sure you want to update this item?\")'><i class='fa fa-pencil'></i></a> &nbsp</td>";
                                                            echo "<td><a href='AddItem.php?deleted_id=" . $row["id"]. "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this item?\")'><i class='fa fa-trash'></i></a></td>";
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
