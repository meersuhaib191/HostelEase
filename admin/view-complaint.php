<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../libs/PHPMailer/src/Exception.php';
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';

// Load email configuration
$emailConfig = include('../php/email_config.php');

if (isset($_POST['status'])) {
    $complaint_id = $_POST['complaint_id'];
    $status = $_POST['status'];

    // Check if the status already exists
    $check_query = "SELECT * FROM complaint_status WHERE complaint_id = '$complaint_id'";
    $check_result = $mysqli->query($check_query);

    if ($check_result->num_rows > 0) {
        // Update existing status
        $query = "UPDATE complaint_status SET status = '$status', updated_at = CURRENT_TIMESTAMP WHERE complaint_id = '$complaint_id'";
    } else {
        // Insert new status record
        $query = "INSERT INTO complaint_status (complaint_id, status) VALUES ('$complaint_id', '$status')";
    }

    if ($mysqli->query($query)) {
        $success_msg = "Complaint status updated successfully!";

        // Fetch student email and details
        $student_query = "
            SELECT u.email, CONCAT(u.firstName, ' ', u.lastName) AS student_name, c.description 
            FROM complaints c
            INNER JOIN userregistration u ON c.student_id = u.id
            WHERE c.id = '$complaint_id'
        ";
        $student_result = $mysqli->query($student_query);

        if ($student_result->num_rows > 0) {
            $student = $student_result->fetch_assoc();
            $student_email = $student['email'];
            $student_name = $student['student_name'];
            $complaint_description = $student['description'];

            // Send email notification to the student
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = $emailConfig['host'];
                $mail->SMTPAuth = true;
                $mail->Username = $emailConfig['username'];
                $mail->Password = $emailConfig['password'];
                $mail->SMTPSecure = $emailConfig['encryption'];
                $mail->Port = $emailConfig['port'];

                $mail->setFrom($emailConfig['from_email'], $emailConfig['from_name']);
                $mail->addAddress($student_email, $student_name);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = "Update on Your Complaint #$complaint_id";
                $mail->Body = "
                    <p>Dear $student_name,</p>
                    <p>Your complaint has been updated with the following status: <strong>$status</strong>.</p>
                    <p><strong>Complaint Description:</strong> $complaint_description</p>
                    <p>Thank you for your patience.</p>
                    <p>Best regards,<br>HostelEase Team</p>
                ";

                $mail->send();
            } catch (Exception $e) {
                $error_msg = "Status updated, but email could not be sent. Error: {$mail->ErrorInfo}";
            }
        }
    } else {
        $error_msg = "Failed to update complaint status. Please try again.";
    }
}
?>


<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hostel Ease</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <link href="../dist/css/style.min.css" rel="stylesheet">
</head>

<body>

    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <header class="topbar" data-navbarbg="skin6">
            <?php include 'includes/navigation.php' ?>
        </header>

        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include 'includes/sidebar.php' ?>
            </div>
        </aside>

        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Student Profile</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Complaints Management</h4>

                                <?php if (isset($success_msg)) { ?>
                                    <div class="alert alert-success" role="alert">
                                        <?php echo $success_msg; ?>
                                    </div>
                                <?php } ?>

                                <?php if (isset($error_msg)) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $error_msg; ?>
                                    </div>
                                <?php } ?>

                                <?php
                                $query = "
                                SELECT c.id, c.description, c.date_submitted, 
                                       IFNULL(cs.status, 'Pending') AS status, 
                                       CONCAT(u.firstName, ' ', u.lastName) AS student_name
                                FROM complaints c
                                INNER JOIN userregistration u ON c.student_id = u.id
                                LEFT JOIN complaint_status cs ON c.id = cs.complaint_id
                                ORDER BY c.date_submitted DESC";

                                $result = $mysqli->query($query);
                                while ($row = $result->fetch_assoc()) {
                                ?>
                                    <div class="complaint-card">
                                        <h5>Complaint #<?php echo $row['id']; ?></h5>
                                        <p><strong>Student:</strong> <?php echo $row['student_name']; ?></p>
                                        <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
                                        <p><strong>Date Submitted:</strong> <?php echo date('d-m-Y H:i', strtotime($row['date_submitted'])); ?></p>
                                        <p>
                                            <strong>Status:</strong>
                                            <?php if ($row['status'] == 'Under Process') { ?>
                                                <span class="badge badge-yellow"><?php echo $row['status']; ?></span>
                                            <?php } elseif ($row['status'] == 'Resolved') { ?>
                                                <span class="badge badge-green"><?php echo $row['status']; ?></span>
                                            <?php } else { ?>
                                                <span class="badge badge-secondary">Pending</span>
                                            <?php } ?>
                                        </p>

                                        <!-- Status Update Form for each complaint -->
                                        <form method="POST" action="">
                                            <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="status" value="Under Process" class="btn btn-warning btn-sm">Mark as Under Process</button>
                                            <button type="submit" name="status" value="Resolved" class="btn btn-success btn-sm">Mark as Resolved</button>
                                        </form>
                                        <br>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include '../includes/footer.php'; ?>
        </div>
    </div>

    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../dist/js/app-style-switcher.js"></script>
    <script src="../dist/js/feather.min.js"></script>
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/extra-libs/c3/d3.min.js"></script>
    <script src="../assets/extra-libs/c3/c3.min.js"></script>
    <script src="../assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="../assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="../dist/js/pages/dashboards/dashboard1.min.js"></script>
    <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
</body>

</html>
