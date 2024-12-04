<?php
session_start();
include('../includes/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['studentId'];
    $day = $_POST['day'];
    $status = $_POST['status'];

    // Prepare the column name dynamically based on the day
    $column = "day" . (int)$day;

    // Prepare and execute the update query
    $query = "UPDATE attendance_details SET $column = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("si", $status, $studentId);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $mysqli->close();
}
?>
