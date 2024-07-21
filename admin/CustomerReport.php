<?php
require_once('tcpdf/tcpdf.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iqlaascollection";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

$sql = "SELECT id, name, phone, address, product, fee, extra_field, price, SUM(qty) AS total_quantity, SUM(price * qty) AS total_sales, SUM(qty) AS total_qty_sum, SUM(price) AS total_price, SUM(extra_field) AS total_fee
        FROM customer";

if ($start_date && $end_date) {
    $sql .= " WHERE date BETWEEN '$start_date' AND '$end_date'";
}

$sql .= " GROUP BY price ORDER BY total_sales DESC";

$result = $conn->query($sql);

function generatePDF($data, $companyName, $totalSales, $totalQtySum, $totalPriceSum, $totalFeeSum) {
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);
    $currentDate = date('Y-m-d'); 

    $html = '<h1>Customer Report</h1>';
    $html .= '<p><strong>Date Printed:</strong> ' . $currentDate . '</p>';
    $html .= '<table border="1" cellpadding="4">
    <tr>
        <th>Customer ID</th>
        <th>Customer Name</th>
        <th>Customer Phone</th>
        <th>Customer Address</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Customer Fee</th>
        <th>Payment Type</th>
        <th>Total Sales</th>
    </tr>';
    foreach ($data as $row) {
        $html .= '<tr>
        <td>' . $row['id'] . '</td>
        <td>' . $row['name'] . '</td>
        <td>' . $row['phone'] . '</td>
        <td>' . $row['address'] . '</td>
        <td>' . $row['product'] . '</td>
        <td>' . $row['total_quantity'] . '</td>
        <td>' . $row['price'] . '</td>
        <td>' . $row['fee'] . '</td>
        <td>' . $row['extra_field'] . '</td>
        <td>$' . number_format($row['total_sales'], 2) . '</td>
        </tr>';
    }
    $html .= '<tr>
    <td colspan="5"><strong>All Total Customer</strong></td>
    <td><strong>' . $totalQtySum . '</strong></td>
    <td colspan="2"><strong>$' . $totalPriceSum . '</strong></td>
    <td><strong>' . $totalFeeSum . '</strong></td>
    <td><strong>$' . number_format($totalSales, 2) . '</strong></td>
    </tr>';
    $html .= '</table>';
    $html .= '<p><strong>Company Name:</strong> ' . $companyName . '</p>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('sales_report.pdf', 'I');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_pdf'])) {
    $data = [];
    $totalSales = 0;
    $totalQtySum = 0;
    $totalPriceSum = 0;
    $totalFeeSum = 0;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
            $totalQtySum += $row['total_qty_sum'];
            $totalPriceSum += $row['total_price'];
            $totalSales += $row['total_sales'];
            $totalFeeSum += $row['total_fee'];
        }
    }
    $companyName = "IQLAAS COLLECTION"; 
    generatePDF($data, $companyName, $totalSales, $totalQtySum, $totalPriceSum, $totalFeeSum);
    exit;
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
                                    <h4 class="card-title">Customer Report</h4>
                                    <h6 class="card-subtitle">Report <code>.List</code></h6>
                                    <div class="container">
                                        <form method="post" action="">
                                            <label for="start_date" class="form-label">Start Date:</label>
                                            <input type="date" id="start_date" name="start_date" class="col-lg-2" value="<?php echo $start_date; ?>">&nbsp;&nbsp;&nbsp;
                                            <label for="end_date" class="form-label">End Date:</label>
                                            <input type="date" id="end_date" name="end_date" class="col-lg-2" value="<?php echo $end_date; ?>">&nbsp;&nbsp;&nbsp;
                                            <input type="submit" value="Filter" class="col-bg-2 btn btn-primary" class="col-sm-3">&nbsp;&nbsp;&nbsp;
                                            <input type="submit" name="generate_pdf" class="btn btn-danger" value="Print">
                                        </form><br>
                                        <div id="sales-report">
                                            <?php
                                            if ($result->num_rows > 0) {
                                                echo "<table class='table table-hover'>
                                                        <thead class='table-dark'>
                                                            <tr>
                                                                <th>Customer ID</th>
                                                                <th>Customer Name</th>
                                                                <th>Customer Phone</th>
                                                                <th>Customer Address</th>
                                                                <th>Product Name</th>
                                                                <th>Quantity</th>
                                                                <th>Price</th>
                                                                <th>Customer Fee</th>
                                                                <th>Payment Type</th>
                                                                <th>Total Sales</th>
                                                            </tr>
                                                        </thead>";
                                                $totalSales = 0;
                                                $totalQtySum = 0;
                                                $totalPriceSum = 0;
                                                $totalFeeSum = 0;
                                                while($row = $result->fetch_assoc()) {
                                                    echo "<tr>
                                                            <td>" . $row["id"]. "</td>
                                                            <td>" . $row["name"]. "</td>
                                                            <td>" . $row["phone"]. "</td>
                                                            <td>" . $row["address"]. "</td>
                                                            <td>" . $row["product"]. "</td>
                                                            <td>" . $row["total_quantity"]. "</td>
                                                            <td>" . $row["price"]. "</td>
                                                            <td>" . $row["fee"]. "</td>
                                                            <td>" . $row["extra_field"]. "</td>
                                                            <td>$" . number_format($row["total_sales"], 2). "</td>
                                                          </tr>";
                                                    $totalSales += $row['total_sales'];
                                                    $totalQtySum += $row['total_quantity'];
                                                    $totalPriceSum += $row['total_quantity'] * $row['price']; 
                                                    $totalFeeSum += $row['total_fee'];
                                                }
                                                echo "<tr>
                                                        <td colspan='5'><h6 style='color: red;'><strong>All Totals Customer</strong><h6></td>
                                                        <td ><h6 style='color: red;'><strong>" . $totalQtySum . "</strong></h6></td>
                                                        <td colspan='2'><h6 style='color: red;'><strong>$" . $totalPriceSum . "</strong></h6></td>
                                                        <td ><h6 style='color: red;'><strong>" . $totalFeeSum . "</strong></h6></td>
                                                        <td ><h6 style='color: red;'><strong>$" . number_format($totalSales, 2) . "</strong></h6></td>
                                                      </tr>";
                                                echo "</table>";
                                                
                                                echo "<h6><p><strong>Company Name: IQLAAS COLLECTION</strong> </p></h6>"; 
                                                echo "<tr>
                                                        <td colspan='3'><h5><strong> Customer Totals</strong></h5></td>
                                                        <td><h4 style='color: red;'><strong>$" . number_format($totalSales) . "</strong></h4></td>
                                                      </tr>";
                                            } else {
                                                echo "0 results";
                                            }
                                            ?>
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
