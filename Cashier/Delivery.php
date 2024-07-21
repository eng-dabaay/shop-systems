<?php
require_once('./tcpdf/tcpdf.php'); 
include "./database/db.php";


if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM delivery WHERE type LIKE '%$search%'";
} else {
    $query = "SELECT * FROM delivery";
}

$result = $conn->query($query);
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Delivery Table</h4>
                                <h6 class="card-subtitle">Add Delivery <code>.table</code></h6>

                                <form action="Delivery.php" method="GET" class="mb-4">
                                    <div class="form-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Search by Delivery Type">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="generate_pdf.php<?php echo isset($_GET['search']) ? '?search=' . urlencode($_GET['search']) : ''; ?>"
                                        class="btn btn-danger ml-2">Print Delivery</a>
                                </form>

                                <div class="container mt-3">
                                    <hr class="my-4">

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-center">
                                            <thead>
                                                <tr>
                                                    <th>DeliveryID</th>
                                                    <th>Customer Name</th>
                                                    <th>Phone No</th>
                                                    <th>Address</th>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Method Payment</th>
                                                    <th>Delivery Type</th>
                                                    <th>Delivery Fee</th>
                                                    <th>SubTotal</th>
                                                    <th>Total Cost</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $subtotal = $row["price"] * $row["qty"];
                                                        $deliveryType = $row["type"];
                                                        $deliveryFee = 0;

                                                        switch ($deliveryType) {
                                                            case 'vekon':
                                                                $deliveryFee = 1.5;
                                                                break;
                                                            case 'bajaj':
                                                                $deliveryFee = 2.5;
                                                                break;
                                                            case 'gaari':
                                                                $deliveryFee = 5;
                                                                break;
                                                            case 'diyaarad':
                                                                $deliveryFee = 50;
                                                                break;
                                                            default:
                                                                $deliveryFee = 0;
                                                        }

                                                        $totalCost = $subtotal + $deliveryFee;

                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["product"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["qty"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["price"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["method"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["type"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($deliveryFee) . "</td>";
                                                        echo "<td>" . htmlspecialchars($subtotal) . "</td>";
                                                        echo "<td>" . htmlspecialchars($totalCost) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
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

</body>

</html>
