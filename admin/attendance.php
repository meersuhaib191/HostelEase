<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

// Fetch month and year from POST request, or use the current month and year as default
$month = isset($_POST['month']) ? (int)$_POST['month'] : date('m');
$year = isset($_POST['year']) ? (int)$_POST['year'] : date('Y');

// Calculate the number of days in the selected month and year
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

// Fetch attendance records for the selected month and year
$studentsQuery = "SELECT * FROM attendance_details WHERE month = ? AND year = ? ORDER BY id ASC";
$stmt = $mysqli->prepare($studentsQuery);
$stmt->bind_param("ii", $month, $year);
$stmt->execute();
$studentsResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>View Attendance - HostelEase</title>
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <style>
        /* Freeze first three columns */
        .frozen-column {
            position: sticky;
            left: 0 ;
        
            background-color: white;
            z-index: 0;
        }
        .frozen-column:nth-child(2) { left: 100px; }
        .frozen-column:nth-child(3) { left: 200px; }
    </style>
</head>
<body>
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <header class="topbar" data-navbarbg="skin6">
            <?php include 'includes/navigation.php'; ?>
        </header>

        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar">
                <?php include 'includes/sidebar.php'; ?>
            </div>
        </aside>

        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1"><?php echo $hostel_name; ?><br><br>Attendance Details</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Search Student</label>
                        <input type="text" id="searchStudent" class="form-control" placeholder="Enter student name...">
                    </div>
                </div>

                <form method="POST" action="attendance.php">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Select Month</label>
                            <select name="month" class="form-control">
                                <?php for ($m = 1; $m <= 12; $m++) {
                                    $monthName = date('F', mktime(0, 0, 0, $m, 10));
                                    echo "<option value='$m' " . ($month == $m ? "selected" : "") . ">$monthName</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Select Year</label>
                            <select name="year" class="form-control">
                                <?php for ($y = 2020; $y <= date('Y'); $y++) {
                                    echo "<option value='$y' " . ($year == $y ? "selected" : "") . ">$y</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-block mt-4">Filter</button>
                        </div>
                    </div>
                </form>


                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Student Attendance for <?php echo date('F Y', strtotime("$year-$month-01")); ?></h4>
                        <div class="table-responsive">
                            <table id="attendance_table" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                        <th class="frozen-column">Student Name</th>
                                        <th class="frozen-column">Course</th>
                                        <th>Batch</th>
                                        <?php for ($day = 1; $day <= $daysInMonth; $day++) { 
                                            $weekday = $weekdays[date('w', strtotime("$year-$month-$day"))];
                                        ?>
                                            <th><?php echo $day; ?><br><small><?php echo $weekday; ?></small></th>
                                        <?php } ?>
                                        <th>Total Days Present</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($student = $studentsResult->fetch_assoc()) { 
                                        $totalDaysPresent = 0; 
                                    ?>
                                        <tr>
                                            <td class="frozen-column"><?php echo $student['student_name']; ?></td>
                                            <td class="frozen-column"><?php echo $student['course']; ?></td>
                                            <td><?php echo $student['batch']; ?></td>
                                            <?php
                                                for ($day = 1; $day <= $daysInMonth; $day++) {
                                                    $attendanceStatus = $student["day$day"];
                                                    if ($attendanceStatus == 'P') $totalDaysPresent++;
                                            ?>
                                                <td class="attendance-cell" data-student-id="<?php echo $student['id']; ?>" data-day="<?php echo $day; ?>" data-status="<?php echo $attendanceStatus; ?>">
                                                    <span class="badge <?php echo $attendanceStatus == 'P' ? 'badge-success' : ($attendanceStatus == 'A' ? 'badge-danger' : 'badge-secondary'); ?>">
                                                        <?php echo $attendanceStatus ?: '-'; ?>
                                                    </span>
                                                </td>
                                            <?php } ?>
                                            <td><?php echo $totalDaysPresent; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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
    <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
    <script>
        document.getElementById('searchStudent').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('#attendance_table tbody tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
       
        $(document).on('click', '.attendance-cell', function() {
    const cell = $(this);
    const currentStatus = cell.data('status');
    const studentId = cell.data('student-id');
    const day = cell.data('day');
    const newStatus = currentStatus === 'P' ? 'A' : (currentStatus === 'A' ? '-' : 'P');

    if (confirm(`Change status from ${currentStatus || '-'} to ${newStatus}?`)) {
        $.ajax({
            url: 'update_attendance.php',
            type: 'POST',
            data: { studentId, day, status: newStatus },
            success: function(response) {
                if (response.trim() === "success") {
                    // Update cell's data and appearance
                    cell.data('status', newStatus);
                    cell.find('.badge')
                        .removeClass('badge-success badge-danger badge-secondary')
                        .addClass(newStatus === 'P' ? 'badge-success' : (newStatus === 'A' ? 'badge-danger' : 'badge-secondary'))
                        .text(newStatus || '-');
                    
                    // Recalculate Total Days Present for the affected row
                    let totalDaysPresent = 0;
                    cell.closest('tr').find('.attendance-cell').each(function() {
                        if ($(this).data('status') === 'P') {
                            totalDaysPresent++;
                        }
                    });

                    // Update the Total Days Present column
                    cell.closest('tr').find('td:last-child').text(totalDaysPresent);
                } else {
                    console.error("Database update failed: ", response);
                    alert("Failed to update attendance.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX error: ", textStatus, errorThrown);
                alert("Failed to update attendance.");
            }
        });
    }
});

   
    </script>
</body>
</html>
