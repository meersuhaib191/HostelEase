<?php
session_start();
include('../libs/includes/dbconn.php');
include('../libs/includes/check-login.php');
check_login();

// Check if room_no is provided in URL
if (isset($_GET['room_no'])) {
    $room_no = $_GET['room_no'];

    // Fetch room details from the database
    $query = "SELECT room_id, room_no, no_of_students, student_details FROM rooms WHERE room_no = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param('s', $room_no);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the room exists
        if ($row = $result->fetch_assoc()) {
            $room_no = $row['room_no'];
            $no_of_students = $row['no_of_students'];
            $student_details = json_decode($row['student_details'], true);
        } else {
            echo "<script>alert('Room not found');</script>";
            echo "<script>window.location.href = 'manage-rooms.php';</script>";
            exit();
        }
    }
}

// Handle form submission to update student details
if (isset($_POST['update'])) {
    $updated_students = [];

    // Loop through the posted student data and update
    for ($i = 0; $i < 4; $i++) {  // Adjusting to 4 students
        $enrollment_no = $_POST['enroll_' . $i];  // Unique name for each student's enrollment
        $student_name = $_POST['name_' . $i];    // Unique name for each student's name

        // If student details are provided, update them, else retain the existing value
        if (!empty($enrollment_no) && !empty($student_name)) {
            $updated_students[] = [
                'enroll' => $enrollment_no,
                'name' => $student_name
            ];
        } elseif (isset($student_details[$i])) {
            // If no new data, retain the existing student details
            $updated_students[] = $student_details[$i];
        }
    }

    // Encode the updated student details as JSON
    $updated_students_json = json_encode($updated_students);

    // Update the database with new student details
    $updateQuery = "UPDATE rooms SET student_details = ? WHERE room_no = ?";
    if ($stmtUpdate = $mysqli->prepare($updateQuery)) {
        $stmtUpdate->bind_param('ss', $updated_students_json, $room_no);
        if ($stmtUpdate->execute()) {
            echo "<script>alert('Room details updated successfully');</script>";
            echo "<script>window.location.href = 'manage-rooms.php';</script>";
        } else {
            echo "<script>alert('Error updating room details');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Room Details - HostelEase</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <!-- Use your existing Bootstrap CSS file -->
    <link href="../dist/css/style.min.css" rel="stylesheet">
</head>

<body>
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <!-- Top Bar -->
        <header class="topbar" data-navbarbg="skin6">
            <?php include 'includes/navigation.php' ?>
        </header>

        <!-- Sidebar -->
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
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Room No: <?php echo $room_no; ?></h4>
                                    <p><strong>Number of Students: </strong><?php echo $no_of_students; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <h4 class="mt-4">Student Details</h4>
                            <?php 
                                // Display existing students or empty fields for up to 4 students
                                for ($i = 0; $i < 4; $i++) {
                                    $enrollment_no = isset($student_details[$i]['enroll']) ? $student_details[$i]['enroll'] : '';
                                    $student_name = isset($student_details[$i]['name']) ? $student_details[$i]['name'] : '';
                                    echo "
                                    <div class='form-group row'>
                                        <div class='col-md-6'>
                                            <label for='enroll_{$i}'>Enrollment No: </label>
                                            <input type='text' class='form-control' id='enroll_{$i}' name='enroll_{$i}' value='{$enrollment_no}'>
                                        </div>
                                        <div class='col-md-6'>
                                            <label for='name_{$i}'>Student Name: </label>
                                            <input type='text' class='form-control' id='name_{$i}' name='name_{$i}' value='{$student_name}'>
                                        </div>
                                    </div>";
                                }
                            ?>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="form-actions text-center">
                        <button type="submit" name="update" class="btn btn-success">Update Room Details</button>
                        <a href="manage-rooms.php" class="btn btn-danger">Cancel</a>
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
</body>

</html>
