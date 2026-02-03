<?php
session_start();
include('../libs/includes/dbconn.php');
include('../libs/includes/check-login.php');
check_login();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Use the logged-in user's ID from the session
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];

    // Prepare the query to select student details and room number
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
    JSON_UNQUOTE(JSON_EXTRACT(r.student_details, '$[*].enroll')) LIKE CONCAT('%', u.enrollment_no, '%')
WHERE 
    u.id = ?";


    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any student data is fetched
        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();  // Fetch the student record
            $stmt->close();
        } else {
            // If no student is found with the given ID
            echo "<script>alert('No student found with the given ID.'); window.location.href='dashboard.php';</script>";
            exit;
        }
    } else {
        // Error preparing statement
        echo "<p>Error preparing statement.</p>";
        exit;
    }
} else {
    // If the user is not logged in
    echo "<script>alert('Please log in to view your profile.'); window.location.href='login.php';</script>";
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
            <?php include '../libs/includes/student-navigation.php'?>
        </header>
        
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include '../libs/includes/student-sidebar.php'?>
            </div>
        </aside>
        
        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
            
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <div class="col-7 align-self-center">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">My Profile</h4>
                    </div>
                                <br> <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Enrollment No:</strong> <?php echo $student['enrollment_no']; ?></p>
                                            <p><strong>Full Name:</strong> <?php echo $student['full_name']; ?></p>
                                            <p><strong>Course:</strong> <?php echo $student['course']; ?></p>
                                            <p><strong>Batch:</strong> <?php echo $student['batch']; ?></p>
                                            <p><strong>Room No:</strong> <?php echo $student['room_no'] ? $student['room_no'] : "Not Assigned"; ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Gender:</strong> <?php echo $student['gender']; ?></p>
                                            <p><strong>Address:</strong> <?php echo $student['address'] ? $student['address'] : "Address not available"; ?></p>
                                            <p><strong>Contact No:</strong> <?php echo $student['contactNo']; ?></p>
                                            <p><strong>Email:</strong> <?php echo $student['email']; ?></p>
                                            <p><strong>Registration Date:</strong> <?php echo $student['regDate']; ?></p>
                                          
                                        </div>
                                    </div>
                                
                                <a href="dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include '../libs/includes/footer.php' ?>
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
