<?php
include('../includes/dbconn.php'); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $dealer = $_POST['dealer'];
    $date = $_POST['date'];

    // Updated query to include the dealer attribute
    $query = "UPDATE expenditure SET amount=?, description=?, dealer=?, date=? WHERE id=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('dsssi', $amount, $description, $dealer, $date, $id);
    
    if ($stmt->execute()) {
        header('Location: expenditures.php?msg=Expenditure updated successfully');
    } else {
        header('Location: expenditures.php?error=Failed to update expenditure');
    }
}
?>
