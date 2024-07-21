<?php
include "./database/db.php";

if(isset($_GET['search'])){
    $search = $_GET['search'];
    $query = "SELECT * FROM orders WHERE itemname LIKE '%$search%'";
} else {
    $query = "SELECT * FROM orders";
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
                                <h4 class="card-title">Inventory Lists</h4>
                                <h6 class="card-subtitle">Inventory <code>.List</code></h6>

                                <div class="container">
                                    <form action="Inventory.php" method="GET" class="mb-4">
                                        <div class="form-group">
                                            <input type="text" name="search" class="form-control"
                                                placeholder="Search by item name">
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
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $subtotal = $row["qty"] * $row["price"];
                                                        echo "<tr>";
                                                        echo "<td>" . $row["id"] . "</td>";
                                                        echo "<td>" . $row["itemname"] . "</td>";
                                                        echo "<td>" . $row["des"] . "</td>";
                                                        echo "<td>" . $row["qty"] . "</td>";
                                                        echo "<td>" . $row["price"] . "</td>";
                                                        echo "<td>" . $row["date"] . "</td>";
                                                        echo "<td class='subtotal' style='display:none;'>" . $subtotal . "</td>"; 
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='10'>No records found.</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center mb-3">
                                        <button class="btn btn-danger" onclick="printInventory()">Print Inventory List</button>
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
        function printInventory() {
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Inventory List</title>');
            printWindow.document.write('<style>table, th, td { border: 1px solid black; border-collapse: collapse; padding: 8px; }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h1>Inventory List</h1>');
            printWindow.document.write('<table>');
            printWindow.document.write('<thead><tr><th>#</th><th>Order Name</th><th>Description</th><th>Quantity</th><th>Price</th><th>Date</th></tr></thead>');

            var tableRows = document.querySelectorAll('.table-responsive table tbody tr');
            tableRows.forEach(function(row) {
                var cells = row.cells;
                printWindow.document.write('<tr>');
                for (var i = 0; i < cells.length - 1; i++) {
                    printWindow.document.write('<td>' + cells[i].innerHTML + '</td>');
                }
                printWindow.document.write('</tr>');
            });

            var subtotal = 0;
            tableRows.forEach(function(row) {
                subtotal += parseInt(row.querySelector('.subtotal').innerHTML);
            });

            printWindow.document.write('<tfoot>');
            printWindow.document.write('<tr><td colspan="4">Total</td><td colspan="2">' + subtotal + '</td><td></td></tr>');
            printWindow.document.write('</tfoot>');

            printWindow.document.write('</table>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>

</body>
</html>
