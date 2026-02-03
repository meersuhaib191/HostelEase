<?php
session_start();
include('../libs/includes/dbconn.php');
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

// Get the current month name
$currentMonth = date('F');

// Get bill per day from session
$billPerDay = isset($_SESSION['billPerDay']) ? $_SESSION['billPerDay'] : 0;
$response = ['status' => 'error', 'errors' => []];  // Track multiple errors

// Fetch student attendance details
$query = "SELECT u.email, u.firstName, u.lastName, a.enrollment_no, 
    SUM(CASE WHEN a.day1 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day2 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day3 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day4 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day5 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day6 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day7 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day8 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day9 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day10 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day11 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day12 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day13 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day14 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day15 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day16 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day17 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day18 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day19 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day20 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day21 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day22 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day23 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day24 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day25 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day26 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day27 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day28 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day29 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day30 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day31 = 'P' THEN 1 ELSE 0 END) AS days_present
FROM userregistration u
JOIN attendance_details a ON u.enrollment_no = a.enrollment_no
GROUP BY u.enrollment_no";

$result = $mysqli->query($query);

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'meersuhaib191@gmail.com'; // Use environment variable or external config
$mail->Password = 'yxtxcnzygdzrpgim'; // Use environment variable or external config
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->SMTPDebug = 2; // Enable verbose debug output

while ($student = $result->fetch_assoc()) {
    $daysPresent = $student['days_present'];
    $totalBill = $daysPresent * $billPerDay;

    // Generate Google Pay UPI link
    $upiId = 'meersuhaib191-3@okhdfcbank';
    $upiLink = "https://pay.google.com/gp/w/u/0/upi/pay?pa=$upiId&pn=Hostel%20Management%20Team&am=" . urlencode($totalBill) . "&cu=INR";

    // Configure email for each student
    $mail->clearAddresses();
    $mail->addAddress($student['email']);
    $mail->Subject = "Mess Bill for $currentMonth";
    $mail->Body = "Dear {$student['firstName']} {$student['lastName']},\n\n"
                . "The bill for the month of $currentMonth has been generated.\n\n"
                . "Days Present: $daysPresent\n"
                . "Daily Rate: ₹" . number_format($billPerDay, 2) . "\n"
                . "Total Amount Payable: ₹" . number_format($totalBill, 2) . "\n\n"
                . "Please pay via this link: $upiLink\n\n"
                . "Thank you,\nHostel Management Team";

    if (!$mail->send()) {
        // Collect errors
        $response['errors'][] = "Error sending email to {$student['email']}: " . $mail->ErrorInfo;
    }
}

// Check if any errors occurred during the email sending process
if (empty($response['errors'])) {
    $response['status'] = 'success';
} else {
    $response['status'] = 'error';
}

echo json_encode($response);
?>
