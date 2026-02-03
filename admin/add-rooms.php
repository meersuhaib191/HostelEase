<?php
session_start();
include('../libs/includes/dbconn.php');
include('../libs/includes/check-login.php');
check_login();

if (isset($_POST['submit'])) {
    $room_no = $_POST['rmno'];

    // Prepare associative array for student details
    $student_details = [];

    if (!empty($_POST['student1_name']) && !empty($_POST['student1_enroll'])) {
        $student_details['student1'] = [
            'name' => $_POST['student1_name'],
            'enroll' => $_POST['student1_enroll']
        ];
    }
    
    if (!empty($_POST['student2_name']) && !empty($_POST['student2_enroll'])) {
        $student_details['student2'] = [
            'name' => $_POST['student2_name'],
            'enroll' => $_POST['student2_enroll']
        ];
    }
    
    if (!empty($_POST['student3_name']) && !empty($_POST['student3_enroll'])) {
        $student_details['student3'] = [
            'name' => $_POST['student3_name'],
            'enroll' => $_POST['student3_enroll']
        ];
    }

    if (!empty($_POST['student4_name']) && !empty($_POST['student4_enroll'])) {
        $student_details['student4'] = [
            'name' => $_POST['student4_name'],
            'enroll' => $_POST['student4_enroll']
        ];
    }

    // Count the number of students
    $no_of_students = count($student_details);

    // Convert the student details array to JSON
    $student_details_json = json_encode($student_details);

    // Check if room already exists
    $sql = "SELECT room_no FROM rooms WHERE room_no = ?";
    $stmt1 = $mysqli->prepare($sql);
    $stmt1->bind_param('i', $room_no);
    $stmt1->execute();
    $stmt1->store_result();
    $row_cnt = $stmt1->num_rows;

    if ($row_cnt > 0) {
        echo "<script>alert('Room already exists!');</script>";
    } else {
        // Insert room and student details into the database
        $query = "INSERT INTO rooms (room_no, no_of_students, student_details) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iis', $room_no, $no_of_students, $student_details_json);
        $stmt->execute();
        echo "<script>alert('Room has been added');</script>";
        echo "<script>window.location.href = 'manage-rooms.php';</script>";
    }
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
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Edit Room Details</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <form method="POST">
                <div class="row">
    <!-- Room No Field -->
    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Room No</h4>
                <div class="form-group">
                    <input type="text" name="rmno" placeholder="Enter Room Number" class="form-control" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Student 1 Name and Enrollment No Field -->
    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Student 1 Name</h4>
                <div class="form-group">
                    <input type="text" name="student1_name" placeholder="Enter Student 1 Name" class="form-control" required>
                    <input type="text" name="student1_enroll" placeholder="Enter Enrollment No" class="form-control mt-2" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Student 2 Name and Enrollment No Field -->
    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Student 2 Name</h4>
                <div class="form-group">
                    <input type="text" name="student2_name" placeholder="Enter Student 2 Name" class="form-control">
                    <input type="text" name="student2_enroll" placeholder="Enter Enrollment No" class="form-control mt-2">
                </div>
            </div>
        </div>
    </div>

    <!-- Student 3 Name and Enrollment No Field -->
    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Student 3 Name</h4>
                <div class="form-group">
                    <input type="text" name="student3_name" placeholder="Enter Student 3 Name" class="form-control">
                    <input type="text" name="student3_enroll" placeholder="Enter Enrollment No" class="form-control mt-2">
                </div>
            </div>
        </div>
    </div>

    <!-- Student 4 Name and Enrollment No Field -->
    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Student 4 Name</h4>
                <div class="form-group">
                    <input type="text" name="student4_name" placeholder="Enter Student 4 Name" class="form-control">
                    <input type="text" name="student4_enroll" placeholder="Enter Enrollment No" class="form-control mt-2">
                </div>
            </div>
        </div>
    </div>
</div>


                    <div class="form-actions">
                        <div class="text-center">
                            <button type="submit" name="submit" class="btn btn-success">Add Room</button>
                            <a href="manage-rooms.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </form>
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
    <script src="../assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="../dist/js/pages/dashboards/dashboard1.min.js"></script>
    <div class="col-sm-12 col-md-6 col-lg-4" id="studentFieldsContainer">
    <!-- Dynamic student name fields will appear here based on JavaScript -->
</div>



</body>

</html>
