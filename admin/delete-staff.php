<?php
include('../libs/includes/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);

    $query = "DELETE FROM staff WHERE id=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "Staff record deleted successfully!";
    } else {
        echo "Failed to delete staff record.";
    }
    $stmt->close();
}
?>
