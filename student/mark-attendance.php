<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

// Fetch user data for attendance
$userId = $_SESSION['id'];
$currentDay = date('j');
$currentMonth = date('m');
$currentYear = date('Y');
$currentHour = date('H');
$currentMinute = date('i');
$dayColumn = "day" . $currentDay;

// Define cutoff time
$cutoffHour = 11;
$cutoffMinute = 30;

// Initialize variables
$attendanceMarked = false;
$markedStatus = '';

// Check if attendance for today is already marked
$checkQuery = "SELECT $dayColumn FROM attendance_details WHERE id = ? AND month = ? AND year = ?";
$stmt = $mysqli->prepare($checkQuery);
$stmt->bind_param('iii', $userId, $currentMonth, $currentYear);
$stmt->execute();
$stmt->bind_result($markedStatus);
if ($stmt->fetch() && !empty($markedStatus)) {
    $attendanceMarked = true;
}
$stmt->close();

// Handle attendance marking by student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!$attendanceMarked && ($currentHour < $cutoffHour || ($currentHour == $cutoffHour && $currentMinute <= $cutoffMinute))) {
        $action = $_POST['action'];

        // Insert or update attendance
        $stmt = $mysqli->prepare("
            INSERT INTO attendance_details (id, month, year, $dayColumn, days_present)
            VALUES (?, ?, ?, ?, CASE WHEN ? = 'P' THEN 1 ELSE 0 END)
            ON DUPLICATE KEY UPDATE 
                $dayColumn = VALUES($dayColumn),
                days_present = CASE WHEN ? = 'P' THEN days_present + 1 ELSE days_present END
        ");
        $stmt->bind_param('iiisis', $userId, $currentMonth, $currentYear, $action, $action, $action);
        $stmt->execute();
        $stmt->close();

        $attendanceMarked = true;
        $markedStatus = $action;
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
    <style>
        .attendance-box {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn-disabled {
            pointer-events: none;
            opacity: 0.6;
        }

        .attendance-button {
            font-size: 16px;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            width: 100%;
        }

        .attendance-message {
            font-size: 16px;
            font-weight: bold;
            color: #6c757d;
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
            <?php include '../includes/student-navigation.php' ?>
        </header>

        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include '../includes/student-sidebar.php' ?>
            </div>
        </aside>

        <div class="page-wrapper">

            <div class="container">
                <div class="attendance-box text-center">
                    <h4 class="mb-4">Mark Attendance for Today</h4>
                    <form method="POST" id="attendanceForm">
                        <input type="hidden" name="action" id="attendanceAction">
                        <button type="button" id="presentButton" class="btn btn-success attendance-button"
                            onclick="markAttendance('P')" 
                            <?php echo ($attendanceMarked || ($currentHour > $cutoffHour || ($currentHour == $cutoffHour && $currentMinute > $cutoffMinute))) ? 'disabled' : ''; ?>>
                            Present
                        </button>
                        <button type="button" id="absentButton" class="btn btn-danger attendance-button"
                            onclick="markAttendance('A')" 
                            <?php echo ($attendanceMarked || ($currentHour > $cutoffHour || ($currentHour == $cutoffHour && $currentMinute > $cutoffMinute))) ? 'disabled' : ''; ?>>
                            Absent
                        </button>
                    </form>
                    <p id="attendanceMessage" class="attendance-message mt-3"><?php 
                        if ($attendanceMarked) {
                            echo "Attendance marked as '" . ($markedStatus === 'P' ? 'Present' : 'Absent') . "'";
                        } elseif ($currentHour > $cutoffHour || ($currentHour == $cutoffHour && $currentMinute > $cutoffMinute)) {
                            echo "Attendance marking is closed for today.";
                        }
                    ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php' ?>
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
    <script>
        function markAttendance(status) {
            if (<?php echo json_encode($attendanceMarked || ($currentHour > $cutoffHour || ($currentHour == $cutoffHour && $currentMinute > $cutoffMinute))); ?>) {
                alert("You cannot mark attendance at this time.");
                return;
            }

            document.getElementById('attendanceAction').value = status;
            document.getElementById('attendanceForm').submit();
        }
    </script>
</body>

</html>
