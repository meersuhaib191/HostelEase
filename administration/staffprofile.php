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

    // Prepare the query to select staff details
    $query = "SELECT 
        id,
        staff_name,
        role,
        contact,
        email,
        address,
        hire_date,
        status,
        created_at,
        updated_at
    FROM 
        staff 
    WHERE 
        id = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any staff data is fetched
        if ($result->num_rows > 0) {
            $staff = $result->fetch_assoc(); // Fetch the staff record
            $stmt->close();
        } else {
            // If no staff is found with the given ID
            echo "<script>alert('No staff found with the given ID.'); window.location.href='dashboard.php';</script>";
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
    <title>HostelEase - Staff Profile</title>
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
            <?php include '../libs/includes/staff-navigation.php'; ?>
        </header>
        
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include '../libs/includes/staff-sidebar.php'; ?>
            </div>
        </aside>
        
        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 align-self-center">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">My Profile</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Staff Name:</strong> <?php echo $staff['staff_name']; ?></p>
                                        <p><strong>Role:</strong> <?php echo $staff['role']; ?></p>
                                        <p><strong>Contact:</strong> <?php echo $staff['contact']; ?></p>
                                        <p><strong>Email:</strong> <?php echo $staff['email']; ?></p>
                                       
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Address:</strong> <?php echo $staff['address'] ? $staff['address'] : "Not Available"; ?></p>
                                        <p><strong>Status:</strong> <?php echo ucfirst($staff['status']); ?></p>
                                        <p><strong>Created At:</strong> <?php echo $staff['created_at']; ?></p>
                                        <p><strong>Last Updated:</strong> <?php echo $staff['updated_at']; ?></p>
                                    </div>
                                </div>

                                <a href="dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include '../libs/includes/footer.php'; ?>
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
