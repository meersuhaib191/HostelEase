<?php
session_start();
include('../libs/includes/dbconn.php');
include('../libs/includes/check-login.php');
check_login();

if (isset($_POST['update'])) {
    $expenditureId = $_POST['id'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    
    $updateQuery = "UPDATE expenditure SET amount = ?, description = ?, date = ? WHERE id = ?";
    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param('dssi', $amount, $description, $date, $expenditureId);
    
    if ($stmt->execute()) {
        echo "<script>alert('Expenditure updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating expenditure');</script>";
    }
}

$id = $_GET['id'];
$query = "SELECT * FROM expenditure WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Expenditure</title>
</head>
<body>
    <h3>Edit Expenditure</h3>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label>Amount:</label>
        <input type="number" step="0.01" name="amount" value="<?php echo $row['amount']; ?>" required><br>
        <label>Description:</label>
        <input type="text" name="description" value="<?php echo $row['description']; ?>" required><br>
        <label>Date:</label>
        <input type="date" name="date" value="<?php echo $row['date']; ?>" required><br>
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
