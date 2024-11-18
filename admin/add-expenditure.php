<?php
session_start();
include('../includes/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $dealer = $_POST['dealer']; // Capture the dealer input

    $query = "INSERT INTO expenditure (amount, description, date, dealer) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('dsss', $amount, $description, $date, $dealer);

    if ($stmt->execute()) {
        header('Location: expenditures.php?msg=Expenditure added successfully');
    } else {
        header('Location: expenditures.php?error=Failed to add expenditure');
    }
}
?>
