<?php
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';
require '../libs/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to, $subject, $body, $hostelname) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'meersuhaib191@gmail.com';
        $mail->Password = 'yxtxcnzygdzrpgim';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('meersuhaib191@gmail.com', $hostelname);
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return 'Email has been sent successfully.';
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('../libs/includes/dbconn.php');

    // Fetch hostel name
    $query = "SELECT hostel_name FROM admin LIMIT 1";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    $hostelname = $row['hostel_name'] ?? 'HostelEase';

    // Retrieve POST data
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    // Send the email
    $result = sendEmail($email, $subject, $body, $hostelname);
    echo $result;
} else {
    echo "Invalid request.";
}


?>
