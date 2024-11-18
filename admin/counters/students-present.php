<?php
// Include database connection file
include('../includes/dbconn.php');

// Get the current day of the month
$current_day_column = 'day' . date('j'); // `day1` for the 1st, `day2` for the 2nd, etc.

// Prepare SQL query to count students present today (assuming "P" means present)
$query = "SELECT COUNT(*) as present_count FROM attendance_details WHERE $current_day_column = 'P'";

if ($result = $mysqli->query($query)) {
    $row = $result->fetch_assoc();
    echo $row['present_count']; // Outputs the count of students present today
} else {
    echo "Error: " . $mysqli->error;
}

// Close the database connection



?>
