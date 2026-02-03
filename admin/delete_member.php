<?php
include('../libs/includes/dbconn.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM messcommittee WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    echo "<script>alert('Committee member deleted successfully.');</script>";
    header("Location: success_page.php"); // Redirect after deletion
    exit();
}
?>
