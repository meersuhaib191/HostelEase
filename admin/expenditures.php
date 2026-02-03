<?php
session_start();
include('../libs/includes/dbconn.php');
include('../libs/includes/check-login.php');
check_login();
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HostelEase - Expenditures</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
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
    
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <header class="topbar" data-navbarbg="skin6">
            <?php include 'includes/navigation.php'?>
        </header>

        <aside class="left-sidebar" data-sidebarbg="skin6">
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <?php include 'includes/sidebar.php'?>
            </div>
        </aside>

        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-7 align-self-center">
                        <h4 class="page-title text-truncate text-dark font-weight-medium mb-1"><?php echo $hostel_name; ?><br><br>Expenditures</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <button type="button" class="btn btn-block btn-md btn-success" data-toggle="modal" data-target="#addExpenditureModal">Add New Expenditure</button>

                                </a>
                                <hr>
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-hover table-bordered no-wrap">
                                    <thead class="thead-dark">
    <tr>
        <th>Id</th>
        <th>Amount</th>
        <th>Dealer</th>
        <th>Description</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
<?php
$ret = "SELECT id, amount, description, dealer, date FROM expenditure ORDER BY date DESC";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
$cnt = 1;

while ($row = $res->fetch_object()) {
    ?>
    <tr>
        <td><?php echo $cnt++; ?></td>
        <td>₹<?php echo number_format($row->amount, 2); ?></td>
        <td><?php echo $row->dealer; ?></td>
        <td><?php echo $row->description; ?></td>
        <td><?php echo date('d-M-Y', strtotime($row->date)); ?></td>
        <td>
    <button class="btn btn-sm btn-success edit-btn" 
        data-id="<?php echo $row->id; ?>" 
        data-amount="<?php echo $row->amount; ?>" 
        data-dealer="<?php echo $row->dealer; ?>" 
        data-description="<?php echo $row->description; ?>" 
        data-date="<?php echo $row->date; ?>">
        Edit
    </button>
    <button class="btn btn-sm btn-danger delete-btn" 
        data-id="<?php echo $row->id; ?>" 
        data-description="<?php echo $row->description; ?>">
        Delete
    </button>
</td>

    </tr>
<?php } ?>
</tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="edit-expenditure.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Expenditure</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group">
                        <label for="edit-amount">Amount</label>
                        <input type="number" class="form-control" name="amount" id="edit-amount" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-dealer">Dealer</label>
                        <input type="text" class="form-control" name="dealer" id="edit-dealer" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-description">Description</label>
                        <input type="text" class="form-control" name="description" id="edit-description" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-date">Date</label>
                        <input type="date" class="form-control" name="date" id="edit-date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="delete-expenditure.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="delete-id">
                    <p>Are you sure you want to delete the expenditure: <strong id="delete-description"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addExpenditureModal" tabindex="-1" role="dialog" aria-labelledby="addExpenditureModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="add-expenditure.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addExpenditureModalLabel">Add New Expenditure</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="add-amount">Amount</label>
                        <input type="number" class="form-control" name="amount" id="add-amount" required>
                    </div>
                    <div class="form-group">
    <label for="dealer">Dealer</label>
    <input type="text" class="form-control" id="dealer" name="dealer" placeholder="Enter dealer name" required>
</div>

                    <div class="form-group">
                        <label for="add-description">Description</label>
                        <input type="text" class="form-control" name="description" id="add-description" required>
                    </div>
                    <div class="form-group">
                        <label for="add-date">Date</label>
                        <input type="date" class="form-control" name="date" id="add-date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Expenditure</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
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
    <script src="../assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="../dist/js/pages/dashboards/dashboard1.min.js"></script>
    <script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
    <script>// Open Edit Modal
$('.edit-btn').on('click', function () {
    let id = $(this).data('id');
    let amount = $(this).data('amount');
    let dealer = $(this).data('dealer');
    let description = $(this).data('description');
    let date = $(this).data('date');

    // Populate the modal with existing data
    $('#edit-id').val(id);
    $('#edit-amount').val(amount);
    $('#edit-dealer').val(dealer);
    $('#edit-description').val(description);
    $('#edit-date').val(date);

    // Show the modal
    $('#editModal').modal('show');
});

// Open Delete Confirmation Modal
$('.delete-btn').on('click', function () {
    let id = $(this).data('id');
    let description = $(this).data('description');

    // Populate the modal with the record info
    $('#delete-id').val(id);
    $('#delete-description').text(description);

    // Show the modal
    $('#deleteModal').modal('show');
});
$('#addExpenditureModal').on('shown.bs.modal', function () {
    $('#add-amount').focus();
});


</script>
</body>
</html>
