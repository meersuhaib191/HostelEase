<?php
session_start();
include('../includes/dbconn.php');

if (isset($_POST['enrollment_no']) && isset($_POST['status'])) {
    $enrollmentNo = $_POST['enrollment_no'];
    $status = $_POST['status'];

    // Update the status in the database
    $query = "UPDATE userregistration SET status = ? WHERE enrollment_no = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss', $status, $enrollmentNo);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>
