<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $adn = "DELETE FROM staff WHERE id=?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Record has been deleted');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HostelEase - Administration Info</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <link href="../assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
</head>

<body>
<div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
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
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1"><?php echo $hostel_name; ?><br><br>Administration Staff Info</h4>
                </div>
                <div class="col-5 align-self-center">
                    <a href="add_staff.php" class="btn btn-primary float-right">Add New Staff Member</a>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-subtitle">Displaying all administration staff .</h6>
                            <div class="form-group">
                                <input type="text" id="searchBox" class="form-control" placeholder="Search by Name or Role" onkeyup="filterTable()">
                            </div>
                            <div class="table-responsive">
    <table id="staffTable" class="table table-striped table-hover table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Staff Name</th>
                <th>Role</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $ret = "SELECT * FROM staff";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute();
        $res = $stmt->get_result();
        $cnt = 1;
        while ($row = $res->fetch_object()) {
            ?>
            <tr>
                <td><?php echo $cnt; ?></td>
                <td><?php echo htmlspecialchars($row->staff_name); ?></td>
                <td><?php echo htmlspecialchars($row->role); ?></td>
                <td><?php echo htmlspecialchars($row->contact); ?></td>
                <td><?php echo htmlspecialchars($row->email); ?></td>
                <td>
                    <!-- Edit Button -->
                    <a href="javascript:void(0);" 
   class="btn btn-sm btn-success edit-btn" 
   data-id="<?php echo $row->id; ?>" 
   title="Edit Record">
   <i class="icon-pencil"></i> Edit
</a>

<a href="javascript:void(0);" 
   class="btn btn-sm btn-danger delete-btn" 
   data-id="<?php echo $row->id; ?>" 
   data-name="<?php echo htmlspecialchars($row->staff_name); ?>" 
   title="Delete Record">
   <i class="icon-close"></i> Delete
</a>

                </td>
            </tr>
            <?php
            $cnt++;
        }
        ?>
        </tbody>
    </table>
<
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- Edit Staff Modal -->
<div class="modal fade" id="editStaffModal" tabindex="-1" role="dialog" aria-labelledby="editStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="edit-staff.php" method="POST">
                <div class="modal-header">
                <h5 class="modal-title" id="editStaffModalLabel">Edit Staff Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="editStaffForm">
                    <input type="hidden" id="staffId" name="id">
                    <div class="mb-3">
                        <label for="staffName" class="form-label">Staff Name</label>
                        <input type="text" class="form-control" id="staffName" name="staff_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="staffRole" class="form-label">Role</label>
                        <input type="text" class="form-control" id="staffRole" name="role" required>
                    </div>
                    <div class="mb-3">
                        <label for="staffContact" class="form-label">Contact</label>
                        <input type="text" class="form-control" id="staffContact" name="contact" required>
                    </div>
                    <div class="mb-3">
                        <label for="staffEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="staffEmail" name="email" required>
                        <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteStaffModal" tabindex="-1" role="dialog" aria-labelledby="deleteStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteStaffForm" method="POST" action="delete-staff.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteStaffModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the record for <strong id="staffToDelete"></strong>? This action cannot be undone.</p>
                    <input type="hidden" id="deleteStaffId" name="id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
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

$(document).ready(function () {
    // Open modal and fetch staff data
    $('.edit-btn').on('click', function () {
        var staffId = $(this).data('id');

        // Fetch staff data
        $.ajax({
            url: 'fetch-staff.php',
            type: 'GET',
            data: { id: staffId },
            dataType: 'json',
            success: function (response) {
                // Populate modal fields with data
                $('#staffId').val(response.id);
                $('#staffName').val(response.staff_name);
                $('#staffRole').val(response.role);
                $('#staffContact').val(response.contact);
                $('#staffEmail').val(response.email);

                // Show modal
                $('#editStaffModal').modal('show');
            }
        });
    });
    $(document).ready(function () {
    // Open delete modal
    $('.delete-btn').on('click', function () {
        var staffId = $(this).data('id');
        var staffName = $(this).data('name');

        // Set modal fields
        $('#deleteStaffId').val(staffId);
        $('#staffToDelete').text(staffName);

        // Show delete modal
        $('#deleteStaffModal').modal('show');
    });

    // Handle delete form submission
    $('#deleteStaffForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: 'delete-staff.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                alert(response);
                $('#deleteStaffModal').modal('hide');
                location.reload(); // Reload the page to refresh the table
            }
        });
    });
});

    // Submit updated data
    $('#editStaffForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: 'update-staff.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                alert(response);
                $('#editStaffModal').modal('hide');
                location.reload(); // Reload the page to refresh the table
            }
        });
    });
});


function filterTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchBox");
    filter = input.value.toUpperCase();
    table = document.getElementById("staffTable");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        tr[i].style.display = "none";
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    break;
                }
            }
        }
    }
}
</script>

</body>
</html>







