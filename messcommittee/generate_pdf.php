<?php
require '../libs/fpdf/fpdf.php';  // Include the FPDF library

// Check if the required session data is set
session_start();
if (!isset($_SESSION['billPerDay']) || !isset($_SESSION['students'])) {
    die('Error: Bill data not available.');
}

// Fetch the students and bill per day
$billPerDay = $_SESSION['billPerDay'];
$students = $_SESSION['students'];  // Ensure this array has all the necessary student data

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Set font for the PDF
$pdf->SetFont('Arial', 'B', 16);

// Add a title to the PDF
$pdf->Cell(200, 10, 'Hostel Billing Details', 0, 1, 'C');

// Add table headers
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(20, 10, 'Sr. No.', 1);
$pdf->Cell(50, 10, 'Student Name', 1);
$pdf->Cell(30, 10, 'Days Present', 1);
$pdf->Cell(30, 10, 'Daily Bill', 1);
$pdf->Cell(30, 10, 'Total Bill', 1);
$pdf->Ln();

// Add the student data to the table
$pdf->SetFont('Arial', '', 12);
$counter = 1;
foreach ($students as $student) {
    $totalBill = $student['days_present'] * $billPerDay;
    $pdf->Cell(20, 10, $counter++, 1);
    $pdf->Cell(50, 10, $student['firstName'] . ' ' . $student['lastName'], 1);
    $pdf->Cell(30, 10, $student['days_present'], 1);
    $pdf->Cell(30, 10, number_format($billPerDay, 2), 1);
    $pdf->Cell(30, 10, number_format($totalBill, 2), 1);
    $pdf->Ln();
}

// Get the current month name
$currentMonth = date('F');  // Example: "November"

// Output the PDF (either to browser or download)
$pdf->Output('D', 'Bill_for_the_month_of_' . $currentMonth . '.pdf');
?>
