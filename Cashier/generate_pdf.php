<?php
require_once('./tcpdf/tcpdf.php'); 
include "./database/db.php";

// Get the search term if available
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Define the query based on search term
$query = "SELECT * FROM delivery";
if (!empty($search)) {
    $query .= " WHERE type LIKE '%" . $conn->real_escape_string($search) . "%'";
}

// Execute the query
$result = $conn->query($query);

// Create new PDF document
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); // 'P' is for portrait orientation

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Delivery Report');
$pdf->SetSubject('Delivery Report');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Set default header data
$companyName = "Iqlaas Collection";
$companyNumber = "Company Number: 252617878157";
$pdf->SetHeaderData('', 0, $companyName, $companyNumber);

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set font
$pdf->SetFont('helvetica', '', 10);

// Add a page
$pdf->AddPage();

// Title with date
$currentDate = date('l, d F Y'); // Format: Sunday, 14 July 2024
$pdf->Cell(0, 10, 'Delivery Report - ' . $currentDate, 0, 1, 'C');

// Create HTML table
$html = '<table border="1" cellpadding="4">
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
    <tbody>';

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

        $html .= '<tr>
            <td>' . htmlspecialchars($row["id"]) . '</td>
            <td>' . htmlspecialchars($row["name"]) . '</td>
            <td>' . htmlspecialchars($row["phone"]) . '</td>
            <td>' . htmlspecialchars($row["address"]) . '</td>
            <td>' . htmlspecialchars($row["product"]) . '</td>
            <td>' . htmlspecialchars($row["qty"]) . '</td>
            <td>' . htmlspecialchars($row["price"]) . '</td>
            <td>' . htmlspecialchars($row["method"]) . '</td>
            <td>' . htmlspecialchars($row["type"]) . '</td>
            <td>' . htmlspecialchars($deliveryFee) . '</td>
            <td>' . htmlspecialchars($subtotal) . '</td>
            <td>' . htmlspecialchars($totalCost) . '</td>
            <td>' . htmlspecialchars($row["status"]) . '</td>
            <td>' . htmlspecialchars($row["date"]) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="14">No records found</td></tr>';
}

$html .= '</tbody></table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('delivery_report.pdf', 'I');
?>
