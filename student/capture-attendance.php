<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

if (!isset($_SESSION['attendance_modal_shown'])) {
    $_SESSION['attendance_modal_shown'] = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $_SESSION['attendance_modal_shown'] = true; // Mark the modal as shown when the form is submitted
    $currentDay = date('j'); // Current day (1-31)
    $currentMonth = date('m'); // Current month
    $currentYear = date('Y'); // Current year
    $currentTime = strtotime(date('H:i:s')); // Current time
    $cutoffTime = strtotime("24:30:00"); // Attendance cutoff time

    // Check if the current time is after the cutoff time
    if ($currentTime > $cutoffTime) {
        echo "<script>alert('Attendance marking is closed for today!');</script>";
    } else {
        $enrollmentNo = $_SESSION['enrollment_no']; // Capture the enrollment number from session
        $action = $_POST['action']; // 'P' for Present, 'A' for Absent

        // Check if enrollment number is empty
        if (empty($enrollmentNo)) {
            echo "<script>alert('Enrollment number is not set. Please log in again.');</script>";
            exit;
        }

        // Determine the day column dynamically based on the current day
        $dayColumn = "day" . $currentDay;

        // Construct the query dynamically to update the attendance using enrollment number
        $query = "
            UPDATE attendance_details 
            SET $dayColumn = ?, 
                days_present = CASE WHEN ? = 'P' THEN days_present + 1 ELSE days_present END
            WHERE enrollment_no = ? 
              AND month = ? 
              AND year = ?
        ";

        // Prepare the query statement
        if ($stmt = $mysqli->prepare($query)) {
            // Bind parameters: action ('P' or 'A' for absent), enrollment number, current month, and year
            $stmt->bind_param('sssii', $action, $action, $enrollmentNo, $currentMonth, $currentYear);

            // Execute the query
            if ($stmt->execute()) {
                // Check if the update was successful
                if ($stmt->affected_rows > 0) {
                    // Update the session to ensure modal doesn't show again
                    $_SESSION['attendance_modal_shown'] = true;
                    echo "<script>alert('Attendance marked successfully!');</script>";
                } else {
                    echo "<script>alert('No changes made, check if attendance is already marked for today.');</script>";
                }
            } else {
                echo "<script>alert('Error executing the query.');</script>";
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            // Log any errors related to statement preparation
            echo "<script>alert('Error preparing the query: " . $mysqli->error . "');</script>";
        }
    }
}
?>
