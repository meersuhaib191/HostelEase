<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HostelEase</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <link href="../assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
</head>

<body>
<div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
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
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1"><?php echo $hostel_name; ?><br><br>Student Management</h4>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-subtitle">Displaying all registered students</h6>
                            <div class="form-group">
                                <input type="text" id="searchBox" class="form-control" placeholder="Search by Registration No or Name" onkeyup="filterTable()">
                            </div>
                            <div class="table-responsive">
                                <table id="studentTable" class="table table-striped table-hover table-bordered no-wrap">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Id</th>
                                            <th>Enrollment No.</th>
                                            <th>Name</th>
                                            <th>Course</th>
                                            <th>Batch</th>
                                            <th>Gender</th>
                                            <th>Address</th> <!-- Added Address Column -->
                                            <th>Contact</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php	
                                    $ret = "SELECT * from userregistration";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($row = $res->fetch_object()) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $row->enrollment_no; ?></td>
                                            <td><?php echo $row->firstName . ' ' . $row->middleName . ' ' . $row->lastName; ?></td>
                                            <td><?php echo $row->course; ?></td>
                                            <td><?php echo $row->batch; ?></td>
                                            <td><?php echo $row->gender; ?></td>
                                            <td><?php echo $row->address; ?></td> <!-- Display Address -->
                                            <td><?php echo $row->contactNo; ?></td>
                                            <td><?php echo $row->email; ?></td>
                                            
                                            <td>
                                                <!-- Green box with 'View Full Details' text -->
                                                <a href="students-profile.php?id=<?php echo $row->id; ?>" 
                                                   class="btn btn-success" 
                                                   style="color: white; padding: 10px 20px; text-decoration: none;">
                                                   View Full Details
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
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
<script src="../assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../dist/js/pages/datatable/datatable-basic.init.js"></script>
<script>
function filterTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchBox");
    filter = input.value.toUpperCase();
    table = document.getElementById("studentTable");
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
