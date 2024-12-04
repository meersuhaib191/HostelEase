<?php
// Include the database connection file
include('../includes/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Prepare and execute the DELETE query
    $query = "DELETE FROM expenditure WHERE id=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        header('Location: expenditures.php?msg=Expenditure deleted successfully');
    } else {
        header('Location: expenditures.php?error=Failed to delete expenditure');
    }
}
?>
