<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

// Fetch complaints, related student details, and status
$query = "
    SELECT 
        c.id AS complaint_id, 
        c.description AS complaint_description, 
        c.date_submitted, 
        u.firstName, 
        u.lastName,
        IFNULL(cs.status, 'Pending') AS complaint_status
    FROM complaints c
    INNER JOIN userregistration u ON c.student_id = u.id
    LEFT JOIN complaint_status cs ON c.id = cs.complaint_id
    ORDER BY c.date_submitted DESC
";
$result = $mysqli->query($query);
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
    <style>
        .preloader {
            display: block;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: #fff;
        }

        .lds-ripple {
            display: inline-block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .lds-ripple div {
            position: absolute;
            border: 4px solid #007bff;
            opacity: 1;
            border-radius: 50%;
            animation: ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }

        .lds-ripple div:nth-child(2) {
            animation-delay: -0.5s;
        }

        @keyframes ripple {
            0% {
                top: 36px;
                left: 36px;
                width: 0;
                height: 0;
                opacity: 1;
            }

            100% {
                top: 0px;
                left: 0px;
                width: 72px;
                height: 72px;
                opacity: 0;
            }
        }
    </style>
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
            <?php include '../includes/staff-navigation.php' ?>
        </header>

        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include '../includes/staff-sidebar.php' ?>
            </div>
        </aside>
        <div class="page-wrapper">
            <div class="container-fluid">

                <h4>Student Complaints</h4><br>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="complaints_table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Complaint</th>
                                        <th>Date Submitted</th>
                                        <th>Status</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        $fullName = $row['firstName'] . ' ' . $row['lastName'];
                                    ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $fullName; ?></td>
                                            <td><?php echo $row['complaint_description']; ?></td>
                                            <td><?php echo date('d-m-Y H:i', strtotime($row['date_submitted'])); ?></td>
                                            <td>
                                                <?php if ($row['complaint_status'] === 'Under Process') { ?>
                                                    <span class="badge badge-warning">Under Process</span>
                                                <?php } elseif ($row['complaint_status'] === 'Resolved') { ?>
                                                    <span class="badge badge-success">Resolved</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-secondary">Pending</span>
                                                <?php } ?>
                                            </td>
                                           
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
            <?php include '../includes/footer.php' ?>
        </div>
    </div>

    <!-- JavaScript dependencies -->
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../dist/js/app-style-switcher.js"></script>
    <script src="../dist/js/feather.min.js"></script>
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#complaints_table').DataTable();
        });
    </script>
</body>

</html>
