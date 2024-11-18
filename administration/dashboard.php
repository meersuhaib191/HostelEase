<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the current date details
$currentDay = date('j'); // Numeric day of the month (1-31)
$currentMonth = date('m'); // Numeric month (01-12)
$currentYear = date('Y'); // Full numeric year (e.g., 2024)

// Determine the column name for today's attendance (e.g., day1, day2, etc.)
$dayColumn = "day" . $currentDay;

// Query to fetch all students whose attendance is marked 'p' for the current day
$attendanceQuery = "
    SELECT *
    FROM attendance_details
    WHERE $dayColumn = 'P' AND month = $currentMonth AND year = $currentYear
    ORDER BY student_name ASC
";

$attendanceResult = $mysqli->query($attendanceQuery);

// Query for summary statistics
$query = "
    SELECT 
        (SELECT COUNT(*) FROM userregistration) AS total_students,
        (SELECT COUNT(*) FROM rooms) AS total_rooms,
        (SELECT COUNT(*) FROM rooms) * 4 AS total_capacity,
        (SELECT COUNT(*) FROM attendance_details WHERE $dayColumn = 'P' AND month = MONTH(CURRENT_DATE()) AND year = YEAR(CURRENT_DATE())) AS present_today
";
$result = $mysqli->query($query);
$data = $result->fetch_assoc();
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
            <?php include '../includes/staff-navigation.php' ?>
        </header>

        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include '../includes/staff-sidebar.php' ?>
            </div>
        </aside>

        <div class="page-wrapper">
            <div class="container-fluid">
                <h4>Dashboard</h4><br>
                <div class="card-group">
                    <!-- Cards for statistics -->
                    <div class="card border-right">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo $data['total_students']; ?></h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Registered Students</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-right">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo $data['total_rooms']; ?></h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Rooms</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-right">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo $data['total_capacity']; ?></h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Capacity</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card for Students Present Today -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <h2 class="text-dark mb-1 font-weight-medium" id="presentCount"><?php echo $data['present_today']; ?></h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Students Present Today</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h5 class="mt-4">Students Present Today</h5>

                <?php if ($attendanceResult->num_rows > 0) { ?>
                    <div class="container-fluid">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover text-left">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Student Name</th>
                                        <th>Course</th>
                                        <th>Batch</th>
                                        <th>Days Present</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($attendance = $attendanceResult->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $attendance['id']; ?></td>
                                            <td><?php echo $attendance['student_name']; ?></td>
                                            <td><?php echo $attendance['course']; ?></td>
                                            <td><?php echo $attendance['batch']; ?></td>
                                            <td><?php echo $attendance['days_present']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } else { ?>
                    <p>No attendance records found for this month/year.</p>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php' ?>
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

