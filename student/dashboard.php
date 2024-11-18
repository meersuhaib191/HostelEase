<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

// Handle attendance records display for the selected month/year
$month = isset($_POST['month']) ? (int)$_POST['month'] : date('m');
$year = isset($_POST['year']) ? (int)$_POST['year'] : date('Y');

// Calculate the number of days in the selected month and year
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

// Fetch attendance records for the selected month and year, for the logged-in student only
$userId = $_SESSION['id']; // Get logged-in user ID
$studentsQuery = "SELECT * FROM attendance_details WHERE id = ? AND month = ? AND year = ? ORDER BY id ASC";

$stmt = $mysqli->prepare($studentsQuery);
$stmt->bind_param("iii", $userId, $month, $year);
$stmt->execute();
$studentsResult = $stmt->get_result();

$query = "
    SELECT 
        (SELECT COUNT(*) FROM userregistration) AS total_students,
        (SELECT COUNT(*) FROM rooms) AS total_rooms,
        (SELECT COUNT(*) FROM rooms) * 4 AS total_capacity,
        (SELECT COUNT(*) FROM attendance_details WHERE day" . date('j') . " = 'P' AND month = MONTH(CURRENT_DATE()) AND year = YEAR(CURRENT_DATE())) AS present_today
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
            <?php include '../includes/student-navigation.php' ?>
        </header>

        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include '../includes/student-sidebar.php' ?>
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
                <h5 class="mt-4">Attendance Details</h5>
                <?php if ($studentsResult->num_rows > 0) { ?>
                    <div class="container-fluid">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover text-left">
            <thead class="thead-dark">
                <tr>
                    <th>Student Name</th>
                    <th>Enrollment No</th>
                    <th>Course</th>
                    <th>Batch</th>
                    <th>Days Present</th>
                    <th>Day 1</th>
                    <th>Day 2</th>
                    <th>Day 3</th>
                    <th>Day 4</th>
                    <th>Day 5</th>
                    <th>Day 6</th>
                    <th>Day 7</th>
                    <th>Day 8</th>
                    <th>Day 9</th>
                    <th>Day 10</th>
                    <th>Day 11</th>
                    <th>Day 12</th>
                    <th>Day 13</th>
                    <th>Day 14</th>
                    <th>Day 15</th>
                    <th>Day 16</th>
                    <th>Day 17</th>
                    <th>Day 18</th>
                    <th>Day 19</th>
                    <th>Day 20</th>
                    <th>Day 21</th>
                    <th>Day 22</th>
                    <th>Day 23</th>
                    <th>Day 24</th>
                    <th>Day 25</th>
                    <th>Day 26</th>
                    <th>Day 27</th>
                    <th>Day 28</th>
                    <th>Day 29</th>
                    <th>Day 30</th>
                    <th>Day 31</th>
                    <th>Month</th>
                    <th>Year</th>
                </tr>
            </thead>
          <tbody>
    <?php while ($attendance = $studentsResult->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $attendance['student_name']; ?></td>
            <td><?php echo $attendance['enrollment_no']; ?></td>
            <td><?php echo $attendance['course']; ?></td>
            <td><?php echo $attendance['batch']; ?></td>
            <td><?php echo $attendance['days_present']; ?></td>
            <td><?php echo $attendance['day1']; ?></td>
            <td><?php echo $attendance['day2']; ?></td>
            <td><?php echo $attendance['day3']; ?></td>
            <td><?php echo $attendance['day4']; ?></td>
            <td><?php echo $attendance['day5']; ?></td>
            <td><?php echo $attendance['day6']; ?></td>
            <td><?php echo $attendance['day7']; ?></td>
            <td><?php echo $attendance['day8']; ?></td>
            <td><?php echo $attendance['day9']; ?></td>
            <td><?php echo $attendance['day10']; ?></td>
            <td><?php echo $attendance['day11']; ?></td>
            <td><?php echo $attendance['day12']; ?></td>
            <td><?php echo $attendance['day13']; ?></td>
            <td><?php echo $attendance['day14']; ?></td>
            <td><?php echo $attendance['day15']; ?></td>
            <td><?php echo $attendance['day16']; ?></td>
            <td><?php echo $attendance['day17']; ?></td>
            <td><?php echo $attendance['day18']; ?></td>
            <td><?php echo $attendance['day19']; ?></td>
            <td><?php echo $attendance['day20']; ?></td>
            <td><?php echo $attendance['day21']; ?></td>
            <td><?php echo $attendance['day22']; ?></td>
            <td><?php echo $attendance['day23']; ?></td>
            <td><?php echo $attendance['day24']; ?></td>
            <td><?php echo $attendance['day25']; ?></td>
            <td><?php echo $attendance['day26']; ?></td>
            <td><?php echo $attendance['day27']; ?></td>
            <td><?php echo $attendance['day28']; ?></td>
            <td><?php echo $attendance['day29']; ?></td>
            <td><?php echo $attendance['day30']; ?></td>
            <td><?php echo $attendance['day31']; ?></td>
            <td><?php echo $attendance['month']; ?></td>
            <td><?php echo $attendance['year']; ?></td>
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
