<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if 'id' is passed in the URL and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the query to select student details, room number, and attendance records
    $query = "SELECT 
                u.id AS student_id,
                u.enrollment_no,
                u.full_name,
                u.course,
                u.batch,
                u.gender,
                u.contactNo,
                u.email,
                u.regDate,
                u.address,
                r.room_no
              FROM 
                userregistration u
              LEFT JOIN 
                rooms r 
              ON 
                TRIM(JSON_UNQUOTE(JSON_EXTRACT(r.student_details, '$.student1.enroll'))) = TRIM(u.enrollment_no)
                OR TRIM(JSON_UNQUOTE(JSON_EXTRACT(r.student_details, '$.student2.enroll'))) = TRIM(u.enrollment_no)
                OR TRIM(JSON_UNQUOTE(JSON_EXTRACT(r.student_details, '$.student3.enroll'))) = TRIM(u.enrollment_no)
              WHERE 
                u.id = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any student data is fetched
        if ($result->num_rows > 0) {
            $studentData = $result->fetch_assoc();  // Fetch the student details

            // Fetch attendance data for the student using student_id
            $attendanceQuery = "SELECT month, year, days_present FROM attendance_details WHERE id = ?";
            if ($attendanceStmt = $mysqli->prepare($attendanceQuery)) {
                $attendanceStmt->bind_param('i', $id);  // Bind student_id instead of enrollment_no
                $attendanceStmt->execute();
                $attendanceResult = $attendanceStmt->get_result();

                // Store attendance data in an array
                $attendanceData = [];
                while ($attendanceRow = $attendanceResult->fetch_assoc()) {
                    $attendanceData[] = $attendanceRow;
                }

                $attendanceStmt->close();
            } else {
                // Error preparing attendance query
                echo "<p>Error fetching attendance records.</p>";
                exit;
            }

            $stmt->close();
        } else {
            // If no student is found with the given ID
            echo "<script>alert('No student found with the given ID.'); window.location.href='manage-students.php';</script>";
            exit;
        }
    } else {
        // Error preparing statement
        echo "<p>Error preparing statement.</p>";
        exit;
    }
} else {
    // If ID is not passed or invalid
    echo "<script>alert('Invalid or missing ID.'); window.location.href='manage-students.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>HostelEase</title>
    <link href="../assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
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
        <?php include '../includes/mess-navigation.php'; ?>
    </header>
    <aside class="left-sidebar" data-sidebarbg="skin6">
        <div class="scroll-sidebar" data-sidebarbg="skin6">
            <?php include '../includes/mess-sidebar.php'; ?>
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
                                <h4 class="card-title">Student Profile</h4>
                                <?php if ($studentData) { ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Enrollment No:</strong> <?php echo $studentData['enrollment_no']; ?></p>
                                            <p><strong>Full Name:</strong> <?php echo $studentData['full_name']; ?></p>
                                            <p><strong>Course:</strong> <?php echo $studentData['course']; ?></p>
                                            <p><strong>Batch:</strong> <?php echo $studentData['batch']; ?></p>
                                            <p><strong>Room No:</strong> <?php echo $studentData['room_no'] ? $studentData['room_no'] : "Not Assigned"; ?></p>
                                          
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Gender:</strong> <?php echo $studentData['gender']; ?></p>
                                            <p><strong>Address:</strong> <?php echo $studentData['address'] ? $studentData['address'] : "No address provided"; ?></p>  
                                            <p><strong>Contact No:</strong> <?php echo $studentData['contactNo']; ?></p>
                                            <p><strong>Email:</strong> <?php echo $studentData['email']; ?></p>
                                            <p><strong>Registration Date:</strong> <?php echo $studentData['regDate']; ?></p>
                                        </div>
                                    </div>

                                    <h5 class="mt-4">Attendance Details</h5>
                                    <?php if (!empty($attendanceData)) { ?>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Month</th>
                                                    <th>Year</th>
                                                    <th>Days Present</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($attendanceData as $attendance) { ?>
                                                    <tr>
                                                        <td><?php echo date("F", mktime(0, 0, 0, $attendance['month'], 10)); ?></td>
                                                        <td><?php echo $attendance['year']; ?></td>
                                                        <td><?php echo $attendance['days_present']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <p>No attendance records found.</p>
                                    <?php } ?>
                                <?php } else { ?>
                                    <p>No student information found.</p>
                                <?php } ?>

                                <a href="manage-students.php" class="btn btn-primary mt-3">Back to Student List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include '../includes/footer.php' ?>
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
</body>
</html>
