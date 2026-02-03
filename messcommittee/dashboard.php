<?php
session_start();
include('../libs/includes/dbconn.php');
include('../libs/includes/check-login.php');
check_login();

// Fetch dashboard data and hostel name
$query = "
    SELECT 
        (SELECT COUNT(*) FROM userregistration) AS total_students,
        (SELECT COUNT(*) FROM rooms) AS total_rooms,
        (SELECT COUNT(*) FROM rooms) * 4 AS total_capacity,
        (SELECT COUNT(*) FROM attendance_details WHERE day" . date('j') . " = 'P' AND month = MONTH(CURRENT_DATE()) AND year = YEAR(CURRENT_DATE())) AS present_today,
        (SELECT hostel_name FROM admin LIMIT 1) AS hostel_name
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

    <style>
        .preloader {
            display: block;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background-color: #fff;
        }

        .lds-ripple {
            display: inline-block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .lds-ripple div {
            position: absolute;
            border: 4px solid #007bff;
            opacity: 1;
            border-radius: 50%;
            animation: ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }

        .lds-ripple div:nth-child(2) {
            animation-delay: -0.5s;
        }

        @keyframes ripple {
            0% {
                top: 36px;
                left: 36px;
                width: 0;
                height: 0;
                opacity: 1;
            }

            100% {
                top: 0px;
                left: 0px;
                width: 72px;
                height: 72px;
                opacity: 0;
            }
        }
    </style>
</head>

<body>
   

    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <header class="topbar" data-navbarbg="skin6">
        <?php include '../libs/includes/mess-navigation.php'; ?>
    </header>
    <aside class="left-sidebar" data-sidebarbg="skin6">
        <div class="scroll-sidebar" data-sidebarbg="skin6">
            <?php include '../libs/includes/mess-sidebar.php'; ?>
        </div>
    </aside>

        <div class="page-wrapper">
            <div class="container-fluid">
            <h4><?php echo $hostel_name; ?></h4><br>
                <div class="card-group">
                    <!-- Card for Registered Students -->
                    <div class="card border-right">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo $data['total_students']; ?></h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Registered Students</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card for Total Rooms -->
                    <div class="card border-right">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo $data['total_rooms']; ?></h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Rooms</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="grid"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card for Total Capacity -->
                    <div class="card border-right">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo $data['total_capacity']; ?></h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Capacity</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="layers"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card for Students Present Today -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <h2 class="text-dark mb-1 font-weight-medium"><?php echo $data['present_today']; ?></h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Students Present Today</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="users"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered no-wrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT firstName, lastName, email, contactNo FROM userregistration ORDER BY firstName ASC";
                            $result = $mysqli->query($query);
                            $cnt = 1;
                            while ($row = $result->fetch_object()) {
                            ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo $row->firstName . " " . $row->lastName; ?></td>
                                    <td><?php echo $row->email; ?></td>
                                    <td><?php echo $row->contactNo; ?></td>
                                    <td>
                                        <!-- Example row with Send Email button -->
<a href="javascript:void(0);" onclick="openEmailModal('<?php echo $row->email; ?>')" class="btn btn-sm btn-primary">Send Email</a>

                                        <a href="tel:<?php echo $row->contactNo; ?>" class="btn btn-sm btn-success">Send SMS</a>
                                    </td>
                                </tr>
                            <?php
                                $cnt++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Send Custom Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="emailForm">
                <div class="modal-body">
                    <input type="hidden" name="email" id="email" />
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" name="subject" id="subject" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="body">Email Body</label>
                        <textarea name="body" id="body" class="form-control" rows="6" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>
            </div>

            <?php include '../libs/includes/footer.php' ?>
        </div>
    </div>

    <!-- JavaScript dependencies -->
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
    <script>
    // Function to open the modal with the recipient email
    function openEmailModal(email) {
        $('#email').val(email); // Set the recipient email
        $('#emailModal').modal('show'); // Open the modal
    }

    // Handle form submission with AJAX
    $('#emailForm').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission
        const formData = $(this).serialize();

        $.ajax({
            url: 'send-email.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                alert(response); // Alert the result from send-email.php
                $('#emailModal').modal('hide'); // Close the modal
                $('#emailForm')[0].reset(); // Reset the form
            },
            error: function () {
                alert('There was an error sending the email. Please try again.');
            }
        });
    });
</script>

</body>

</html>
