<?php
include "./database/db.php";

if(isset($_GET['search'])){
    $search = $_GET['search'];
    $query = "SELECT * FROM delivery WHERE type LIKE '%$search%'";
} else {
    $query = "SELECT * FROM delivery";
}

$result = $conn->query($query);

if (isset($_GET["deleted_id"])) {
    $deleted_id = $_GET["deleted_id"];
    $stmt = $conn->prepare("DELETE FROM delivery WHERE id = ?");
    $stmt->bind_param("i", $deleted_id);
    if ($stmt->execute()) {
        echo "<script>alert('Delivery has been deleted successfully'); window.location.href='Delivery.php';</script>";
    } else {
        echo "<script>alert('Error deleting delivery');</script>";
    }
}

if (isset($_POST["status_id"]) && isset($_POST["status"])) {
    $status_id = $_POST["status_id"];
    $status = $_POST["status"];
    $stmt = $conn->prepare("UPDATE delivery SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $status_id);
    if ($stmt->execute()) {
        echo "<script>alert('Delivery status has been updated successfully'); window.location.href='Delivery.php';</script>";
    } else {
        echo "<script>alert('Error updating delivery status');</script>";
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Delivery Table</h4>
                                <h6 class="card-subtitle">Add Delivery <code>.table</code></h6>

                                <form action="Delivery.php" method="GET" class="mb-4">
                                    <div class="form-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search by Delivery Type">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Search</button>
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
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $subtotal = $row["price"] * $row["qty"];
                                                        $deliveryFee = 0;
                                                        switch ($row["type"]) {
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
                                                        }
                                                        $total = $subtotal + $deliveryFee;
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
                                                        echo "<td>" . htmlspecialchars($total) . "</td>";
                                                        echo "<td>";
                                                        echo "<form action='Delivery.php' method='POST'>";
                                                        echo "<input type='hidden' name='status_id' value='" . htmlspecialchars($row["id"]) . "'>";
                                                        echo "<select name='status' onchange='this.form.submit()'>";
                                                        echo "<option value='Pending' " . ($row["status"] == "Pending" ? "selected" : "") . ">Pending</option>";
                                                        echo "<option value='Accepted' " . ($row["status"] == "Accepted" ? "selected" : "") . ">Accepted</option>";
                                                        echo "<option value='Rejected' " . ($row["status"] == "Rejected" ? "selected" : "") . ">Rejected</option>";
                                                        echo "</select>";
                                                        echo "</form>";
                                                        echo "</td>";
                                                        echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                                                        echo "<td><a href='DeliveryForm.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-info' onclick='return confirmUpdate()'> <i class='fa fa-pencil'></i></a> &nbsp;</td>";
                                                        echo "<td><a href='Delivery.php?deleted_id=" . htmlspecialchars($row["id"]) . "' class='btn btn-danger' onclick='return confirmDelete()'><i class='fa fa-trash'></i></a></td>";
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
            return confirm('Do you want to delete this Delivery?');
        }

        function confirmUpdate() {
            return confirm('Do you want to update this Delivery?');
        }
    </script>

</body>

</html>
