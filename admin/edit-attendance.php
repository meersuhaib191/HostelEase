<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $student_id = $_POST['student_id'];
    $day = $_POST['day'];
    $status = $_POST['status']; // 'P' for Present, 'A' for Absent

    // Validate the inputs
    if (empty($student_id) || empty($day) || !in_array($status, ['P', 'A'])) {
        $_SESSION['msg'] = "Invalid input data.";
        header("Location: attendance.php");
        exit();
    }

    // Determine the field to update based on the day
    $attendanceColumn = "day$day"; // Column name (day1, day2, ..., day31)

    // Prepare the SQL query to update attendance
    $sql = "UPDATE attendance_details SET $attendanceColumn = ? WHERE id = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind parameters and execute
        $stmt->bind_param("si", $status, $student_id);
        
        if ($stmt->execute()) {
            // Success: Redirect back with a success message
            $_SESSION['msg'] = "Attendance updated successfully.";
        } else {
            // Error: Redirect back with an error message
            $_SESSION['msg'] = "Error updating attendance.";
        }
        $stmt->close();
    } else {
        $_SESSION['msg'] = "Error preparing SQL query.";
    }

    // Redirect back to the attendance page
    header("Location: attendance.php");
    exit();
}
