<?php
session_start();
include('../includes/dbconn.php');
require '../libs/PHPMailer/src/PHPMailer.php';
require '..//libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

// Insert Member Logic
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $contact = $_POST['contact'];
    $role = $_POST['role'];

    // Check if the email already exists in messcommittee table
    $query = "SELECT * FROM messcommittee WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If email exists, show an error message
        echo "<script>alert('Email already exists! Please use a different email.');</script>";
    } else {
        // Insert into messcommittee table
        $query = "INSERT INTO messcommittee (committee_member_name, email, password, contact_number, role) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssss', $name, $email, $password, $contact, $role);
        $stmt->execute();

        echo "<script>alert('Committee member registered successfully.');</script>";
    }

    // Redirect to avoid multiple submissions
    header("Location: mess_committee.php");
    exit();
}

// Update Committee Member Logic
if (isset($_POST['edit_member'])) {
    $id = $_POST['id']; // Get member ID for editing
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $role = $_POST['role'];

    // Update the committee member in the database
    $query = "UPDATE messcommittee SET committee_member_name = ?, email = ?, contact_number = ?, role = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssssi', $name, $email, $contact, $role, $id);
    $stmt->execute();

    echo "<script>alert('Committee member updated successfully.');</script>";
    return
    exit();
}

// Delete Member Logic
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Delete the member from the database
    $query = "DELETE FROM messcommittee WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    echo "<script>alert('Committee member deleted successfully.');</script>";
    header("Location: mess_committee.php");
    exit();
}

// Fetch mess committee members to display in table
$query = "SELECT * FROM messcommittee";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>HostelEase</title>
    <!-- Custom CSS -->
    <link href="../assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet">

    <script type="text/javascript">
    function valid(){
        if(document.registration.password.value!= document.registration.cpassword.value)
    {
        alert("Password and Confirm Password does not match");
        document.registration.cpassword.focus();
        return false;
    }
        return true;
    }
    </script>
    
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin6">
            <?php include 'includes/navigation.php'?>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include 'includes/sidebar.php'?>
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
        
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1"><?php echo $hostel_name; ?><br><br>Student Registration Form</h4>
                        <div class="d-flex align-items-center">
                            <!-- <nav aria-label="breadcrumb">
                                
                            </nav> -->
                        </div>
                    </div>
                    
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">

            <form method="POST" name="registration" onSubmit="return valid();">
    <div class="row">
        <!-- Committee Member Name Field -->
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Name</h4>
                    <div class="form-group">
                        <input type="text" name="name" id="name" placeholder="Enter Full Name" required class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Number Field -->
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Contact Number</h4>
                    <div class="form-group">
                        <input type="number" name="contact" id="contact" placeholder="Your Contact" required="required" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Field -->
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Email ID</h4>
                    <div class="form-group">
                        <input type="email" name="email" id="email" placeholder="Your Email" onBlur="checkAvailability()" required="required" class="form-control">
                        <span id="user-availability-status" style="font-size:12px;"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Field -->
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Password</h4>
                    <div class="form-group">
                        <input type="password" name="password" id="password" placeholder="Enter Password" required="required" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Password Field -->
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Confirm Password</h4>
                    <div class="form-group">
                        <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" required="required" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Field (Committee Position) -->
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Role</h4>
                    <div class="form-group">
                        <select class="custom-select mr-sm-2" id="role" name="role" required="required">
                            <option selected>Choose Role...</option>
                            <option value="Member">Member</option>
                            <option value="Secretary">Secretary</option>
                            <option value="Chairperson">Chairperson</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit and Reset Buttons -->
    <div class="form-actions">
        <div class="text-center">
            <button type="submit" name="submit" class="btn btn-success">Register</button>
            <button type="reset" class="btn btn-danger">Reset</button>
        </div>
    </div>
</form>
<h4 class="text-center mt-5">Mess Committee Members</h4>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
    <td><?php echo $row['committee_member_name']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['contact_number']; ?></td>
    <td><?php echo $row['role']; ?></td>
    <td>
        <button class="btn btn-primary edit-btn" 
                data-id="<?php echo $row['id']; ?>" 
                data-name="<?php echo $row['committee_member_name']; ?>" 
                data-email="<?php echo $row['email']; ?>" 
                data-contact="<?php echo $row['contact_number']; ?>" 
                data-role="<?php echo $row['role']; ?>">Edit</button>

        <button class="btn btn-danger delete-btn" 
                data-id="<?php echo $row['id']; ?>">Delete</button>
    </td>
</tr>

                <?php endwhile; ?>
            </tbody>
        </table>



            </div>
            <!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Committee Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_contact">Contact Number</label>
                        <input type="text" class="form-control" id="edit_contact" name="contact" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_role">Role</label>
                        <select class="form-control" id="edit_role" name="role" required>
                            <option value="Member">Member</option>
                            <option value="Secretary">Secretary</option>
                            <option value="Chairperson">Chairperson</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="edit_member">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this committee member?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <?php include '../includes/footer.php' ?>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- apps -->
    <!-- apps -->
    <script src="../dist/js/app-style-switcher.js"></script>
    <script src="../dist/js/feather.min.js"></script>
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <!--This page JavaScript -->
    <script src="../assets/extra-libs/c3/d3.min.js"></script>
    <script src="../assets/extra-libs/c3/c3.min.js"></script>
    <script src="../assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="../assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="../dist/js/pages/dashboards/dashboard1.min.js"></script>

    <!-- customs -->
    <script>
    function checkAvailability() {

        $("#loaderIcon").show();
        jQuery.ajax({
        url: "check-availability.php",
        data:'emailid='+$("#email").val(),
        type: "POST",
        success:function(data){
            $("#user-availability-status").html(data);
            $("#loaderIcon").hide();
            },
                error:function ()
            {
                event.preventDefault();
                alert('error');
            }
        });
    }
    // Open Edit Modal and populate data
$(document).on('click', '.edit-btn', function() {
    var memberId = $(this).data('id');
    var memberName = $(this).data('name');
    var memberEmail = $(this).data('email');
    var memberContact = $(this).data('contact');
    var memberRole = $(this).data('role');

    // Populate the modal fields
    $('#edit_name').val(memberName);
    $('#edit_email').val(memberEmail);
    $('#edit_contact').val(memberContact);
    $('#edit_role').val(memberRole);

    // Store the member ID in the hidden field
    $('#editForm').append('<input type="hidden" name="id" value="' + memberId + '">');

    // Show the modal
    $('#editModal').modal('show');
});

// Open Delete Modal and confirm action
$(document).on('click', '.delete-btn', function() {
    var memberId = $(this).data('id');
    $('#confirmDelete').attr('href', 'delete_member.php?id=' + memberId);
    $('#deleteModal').modal('show');
});

    </script>
</body>

</html>
