<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

if (isset($_POST['submit'])) {
    $student_id = $_SESSION['id']; // Assuming the logged-in student's ID is stored in the session
    $description = $mysqli->real_escape_string($_POST['description']);

    $query = "INSERT INTO complaints (student_id, description) VALUES ('$student_id', '$description')";
    if ($mysqli->query($query)) {
        $complaint_id = $mysqli->insert_id; // Get the ID of the newly inserted complaint
        $status_query = "INSERT INTO complaint_status (complaint_id, status) VALUES ('$complaint_id', 'Pending')";
        $mysqli->query($status_query); // Initialize status as 'Pending'

        $success_msg = "Your complaint has been successfully submitted!";
    } else {
        $error_msg = "Failed to submit your complaint. Please try again.";
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
    <link href="../assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <header class="topbar" data-navbarbg="skin6">
            <?php include '../includes/student-navigation.php' ?>
        </header>

        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include '../includes/student-sidebar.php' ?>
            </div>
        </aside>

        <div class="page-wrapper">
            <div class="container-fluid">
                <h4>Register a Complaint</h4><br>
                <div class="card">
                    <div class="card-body">
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
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="description">Complaint Description</label>
                                <textarea name="description" id="description" class="form-control" rows="5" placeholder="Describe your issue here..." required></textarea>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Submit Complaint</button>
                        </form>
                    </div>
                </div>
                <h4>Your Complaints</h4>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Complaint</th>
                                        <th>Status</th>
                                        <th>Date Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $student_id = $_SESSION['id'];
                                    $query = "SELECT c.id AS complaint_id, c.description, cs.status, c.date_submitted 
                                              FROM complaints c 
                                              LEFT JOIN complaint_status cs ON c.id = cs.complaint_id 
                                              WHERE c.student_id = '$student_id' 
                                              ORDER BY c.date_submitted DESC";
                                    $result = $mysqli->query($query);
                                    $cnt = 1;
                                    while ($row = $result->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $row['description']; ?></td>
                                            <td><?php echo $row['status'] ? $row['status'] : 'Pending'; ?></td>
                                            <td><?php echo date('d-m-Y H:i', strtotime($row['date_submitted'])); ?></td>
                                        </tr>
                                    <?php
                                        $cnt++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php include '../includes/footer.php'; ?>
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
</body>

</html>
