<?php
include('../includes/dbconn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $staff_name = $_POST['staff_name'];
    $role = $_POST['role'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    $query = "UPDATE staff SET staff_name=?, role=?, contact=?, email=? WHERE id=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssssi', $staff_name, $role, $contact, $email, $id);

    if ($stmt->execute()) {
        header('Location: administration.php?msg=Staff info updated successfully');
    } else {
        header('Location: administration.php?error=Failed to update staff info');
    }

    $stmt->close();
}
?>
