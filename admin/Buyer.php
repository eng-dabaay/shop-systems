<?php
include "./database/db.php";

if(isset($_GET['search'])){
    $search = $_GET['search'];
    $query = "SELECT * FROM buy WHERE type LIKE '%$search%'";
} else {
    $query = "SELECT * FROM buy";
}

$result = $conn->query($query);

if (isset($_GET["deleted_id"])) {
    $deleted_id = $_GET["deleted_id"];
    $stmt = $conn->prepare("DELETE FROM buy WHERE id = ?");
    $stmt->bind_param("i", $deleted_id);
    if ($stmt->execute()) {
        echo "<script>alert('Buyer has been deleted successfully'); window.location.href='Buyer.php';</script>";
    } else {
        echo "<script>alert('Error deleting buyer');</script>";
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
                        <h3 class="text-themecolor">Buyer Table</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <li class="breadcrumb-item active">Table Buyer</li>
                        </ol>
                    </div>
                    <div class="col-md-7 align-self-center">
                        <a href="BuyerForm.php" class="btn waves-effect waves-light btn btn-info pull-right hidden-sm-down text-white">
                            Add Buyer
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Buyer Table</h4>
                                <h6 class="card-subtitle">Add Buyer <code>.table</code></h6>

                                <form action="Buyer.php" method="GET" class="mb-4">
                                    <div class="form-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search by Buyer name">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>

                                <div class="container mt-3">
                                    <hr class="my-4">

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-center">
                                            <thead>
                                                <tr>
                                                    <th>BuyerID</th>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Company Name</th>
                                                    <th>Targo Type</th>
                                                    <th>Targo Kg</th>
                                                    <th>Total Kg</th>
                                                    <th>Subtotal</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        // Calculate subtotal: qty * price
                                                        $subtotal = $row['qty'] * $row['price'];

                                                        // Calculate total kg based on type
                                                        switch ($row['type']) {
                                                            case 'shibis':
                                                                $totalKg = $row['kg'] * 10; // Example calculation, adjust as per your business logic
                                                                break;
                                                            case 'diyaarad':
                                                                $totalKg = $row['kg'] * 15; // Example calculation, adjust as per your business logic
                                                                break;
                                                            case 'gaari':
                                                            default:
                                                                $totalKg = $row['kg'] * 5; // Example calculation, adjust as per your business logic
                                                                break;
                                                        }

                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["qty"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["price"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["company"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["type"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["kg"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($totalKg) . "</td>";
                                                        echo "<td>" . htmlspecialchars($subtotal) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                                                        echo "<td><a href='BuyerForm.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-info' onclick='return confirmUpdate()'> <i class='fa fa-pencil'></i></a> &nbsp;</td>";
                                                        echo "<td><a href='Buyer.php?deleted_id=" . htmlspecialchars($row["id"]) . "' class='btn btn-danger' onclick='return confirmDelete()'><i class='fa fa-trash'></i></a></td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='11'>No records found</td></tr>";
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
            return confirm('Do you want to delete this Buyer?');
        }

        function confirmUpdate() {
            return confirm('Do you want to update this Buyer?');
        }
    </script>

</body>

</html>
