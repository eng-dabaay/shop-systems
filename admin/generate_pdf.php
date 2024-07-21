<?php
require('fpdf.php'); // Ensure this path is correct and fpdf.php is available

include "./database/db.php"; // Include your database connection file

// Check if an ID is provided via GET request
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch data from database
    $query = "SELECT * FROM buymedicine WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    // Check if data is found
    if ($data) {
        // Create a new PDF document
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12); // Set font to Arial bold 12

        // Add title
        $pdf->Cell(0, 10, 'Medicine Details', 0, 1, 'C');

        // Add data
        $pdf->SetFont('Arial', '', 12); // Set font to Arial regular 12
        $pdf->Cell(0, 10, 'Medicine Name: ' . htmlspecialchars($data["name"]), 0, 1);
        $pdf->Cell(0, 10, 'Quantity: ' . htmlspecialchars($data["qty"]), 0, 1);
        $pdf->Cell(0, 10, 'Price: $' . number_format($data["price"], 2), 0, 1);
        $pdf->Cell(0, 10, 'Subtotal: $' . number_format($data["qty"] * $data["price"], 2), 0, 1);
        $pdf->Cell(0, 10, 'Date: ' . htmlspecialchars($data["date"]), 0, 1);

        // Output the PDF to the browser
        $pdf->Output();
    } else {
        echo "No data found"; // Display a message if no data is found
    }
} else {
    echo "Invalid ID"; // Display a message if no ID is provided
}
?>
