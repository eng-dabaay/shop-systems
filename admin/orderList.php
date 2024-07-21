<?php

include "./database/db.php";

if(isset($_GET['search'])){
    $search = $_GET['search'];
    $query = "SELECT * FROM orders WHERE itemname LIKE '%$search%'";
} else {
    $query = "SELECT * FROM orders";
}

$result = $conn->query($query);

if (isset($_GET["deleted_id"])) {
    $query = "DELETE FROM orders WHERE id = " . $_GET["deleted_id"];
    if ($conn->query($query)) {
        $info = "<script>alert('OrderList has been deleted successfully')</script>";
        header("Location: orderList.php");
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

          <div class="row">
            <!-- column -->
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Inventory Lists</h4>
                  <h6 class="card-subtitle">Inventory <code>.List</code></h6>

                 <div class="container">

                  <form action="orderList.php" method="GET" class="mb-4">
                    <div class="form-group">
                      <input type="text" name="search" class="form-control" placeholder="Search by item name">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                  </form>

                  <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order Name</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>SubTotal</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                                if ($result->num_rows > 0) {
                                    $data = "";
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row["id"] . "</td>";
                                        echo "<td>" . $row["itemname"] . "</td>";
                                        echo "<td>" . $row["des"] . "</td>";
                                        echo "<td>" . $row["qty"] . "</td>";
                                        echo "<td>" . $row["price"] . "</td>";
                                        $subtotal = $row["qty"] * $row["price"];
                                        echo "<td>" . $subtotal . "</td>";
                                        echo "<td>" . $row["date"] . "</td>";
                                        echo "<td><a href='orderList.php?deleted_id=" . $row["id"]. "' class='btn btn-danger'> <i class='fa fa-trash'></i></a> &nbsp;";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No records found.</td></tr>";
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

  </body>
</html>
