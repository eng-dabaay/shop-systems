<?php
include "./database/db.php";

if(isset($_GET['search'])){
    $search = $_GET['search'];
    $query = "SELECT * FROM customer WHERE name LIKE '%$search%'";
} else {
    $query = "SELECT * FROM customer";
}

$result = $conn->query($query);

if (isset($_GET["deleted_id"])) {
    $deleted_id = $_GET["deleted_id"];
    $stmt = $conn->prepare("DELETE FROM customer WHERE id = ?");
    $stmt->bind_param("i", $deleted_id);
    if ($stmt->execute()) {
        echo "<script>alert('Customer has been deleted successfully'); window.location.href='Customer.php';</script>";
    } else {
        echo "<script>alert('Error deleting customer');</script>";
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
                        <h3 class="text-themecolor">Customer Table</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Table Customer</li>
                        </ol>
                    </div>
                    <div class="col-md-7 align-self-center">
                        <a href="CustomerForm.php" class="btn waves-effect waves-light btn btn-info pull-right hidden-sm-down text-white">
                            Add Customer
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Customer Table</h4>
                                <h6 class="card-subtitle">Add Customer <code>.table</code></h6>

                                <form action="Customer.php" method="GET" class="mb-4">
                                    <div class="form-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search by customer name">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>

                                <div class="container mt-3">
                                    <hr class="my-4">

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-center">
                                            <thead>
                                                <tr>
                                                    <th>CustomerID</th>
                                                    <th>Customer Name</th>
                                                    <th>Phone No</th>
                                                    <th>Address</th>
                                                    <th>Product Name</th>
                                                    <th>Description</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Customer Fee</th>
                                                    <th>Extra Field</th>
                                                    <th>SubTotal</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $subtotal = $row["price"] * $row["qty"];
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["product"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["des"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["qty"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["price"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["fee"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["extra_field"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($subtotal) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                                                        echo "<td><a href='CustomerForm.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-info' onclick='return confirmUpdate()'> <i class='fa fa-pencil'></i></a> &nbsp;</td>";
                                                        echo "<td><a href='Customer.php?deleted_id=" . htmlspecialchars($row["id"]) . "' class='btn btn-danger' onclick='return confirmDelete()'><i class='fa fa-trash'></i></a></td>";
                                                        echo "</tr>";
                                                    }
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

    <script>
        function confirmDelete() {
            return confirm('Do you want to delete this customer?');
        }

        function confirmUpdate() {
            return confirm('Do you want to update this customer?');
        }
    </script>

</body>

</html>
