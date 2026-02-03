<?php
    session_start();
    include('../libs/includes/dbconn.php');
    include('../libs/includes/check-login.php');
    check_login();

    if(isset($_POST['update'])){
        $username = $_POST['username'];  // Get the updated username
        $email = $_POST['emailid'];
        $aid = $_SESSION['id'];
        $udate = date('Y-m-d');
        
        // Update query now includes username
        $query = "UPDATE admin SET username = ?, email = ?, updation_date = ? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('sssi', $username, $email, $udate, $aid);
        $stmt->execute();
        echo "<script>alert('Profile has been successfully updated');</script>";
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

    <header class="topbar" data-navbarbg="skin6">
        <?php include '../libs/includes/mess-navigation.php'; ?>
    </header>
    <aside class="left-sidebar" data-sidebarbg="skin6">
        <div class="scroll-sidebar" data-sidebarbg="skin6">
            <?php include '../libs/includes/mess-sidebar.php'; ?>
        </div>
    </aside>
        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">My Profile</h4>
                        <div class="d-flex align-items-center">
                            <h6 class="card-subtitle"><code>You cannot make changes in registered date!</code></h6> 
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <form method="POST">
                    <div class="row">
                    <?php
                        $aid = $_SESSION['id'];
                        $ret = "SELECT * FROM admin WHERE id=?";
                        $stmt = $mysqli->prepare($ret);
                        $stmt->bind_param('i', $aid);
                        $stmt->execute();
                        $res = $stmt->get_result();

                        while ($row = $res->fetch_object()) {
                    ?>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Username</h4>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="username" id="username" value="<?php echo $row->username; ?>" required="required">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Email ID</h4>
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="emailid" id="emailid" value="<?php echo $row->email; ?>" required="required">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Registered Account On</h4>
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="<?php echo $row->reg_date; ?>" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="card-subtitle"><code>Last Updated On: </code> <?php echo $row->updation_date; ?> </h6>

                    <?php } ?>
                    </div>

                    <div class="form-actions">
                        <div class="text-center">
                            <button type="submit" name="update" class="btn btn-success">Make Changes</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
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
    <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
</body>

</html>
