<?php
session_start();
include('../includes/dbconn.php');
include('../includes/check-login.php');
check_login();

// Fetch the bill per day from the session if it's set
$billPerDay = isset($_SESSION['billPerDay']) ? $_SESSION['billPerDay'] : 0;

// Query to get students and their attendance details
$query = "SELECT u.firstName, u.lastName, a.enrollment_no, 
    SUM(CASE WHEN a.day1 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day2 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day3 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day4 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day5 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day6 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day7 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day8 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day9 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day10 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day11 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day12 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day13 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day14 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day15 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day16 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day17 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day18 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day19 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day20 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day21 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day22 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day23 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day24 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day25 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day26 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day27 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day28 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day29 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day30 = 'P' THEN 1 ELSE 0 END +
        CASE WHEN a.day31 = 'P' THEN 1 ELSE 0 END) AS days_present
FROM userregistration u
JOIN attendance_details a ON u.enrollment_no = a.enrollment_no
GROUP BY u.enrollment_no";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$studentsResult = $stmt->get_result();
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
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1"><?php echo $hostel_name; ?><br><br>Billing Details</h4>
                </div>
                <div class="col-5 align-self-center text-right">
                    <button class="btn btn-success" onclick="calculateBill()" style="border-radius: 5px; padding: 10px 20px;">
                        Calculate Bill
                    </button>
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
                                            <th>Student Name</th>
                                            <th>Days Present</th>
                                            <th>Daily Bill Rate</th>
                                            <th>Total Bill</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 1;
                                        while ($student = $studentsResult->fetch_assoc()) {
                                            $daysPresent = $student['days_present'];
                                            echo "<tr>
                                                    <td>{$cnt}</td>
                                                    <td>{$student['firstName']} {$student['lastName']}</td>
                                                    <td>{$daysPresent}</td>
                                                    <td class='bill-per-day'></td>
                                                    <td class='total-bill'></td>
                                                </tr>";
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
        <div class="modal fade" id="billModal" tabindex="-1" role="dialog" aria-labelledby="billModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="billModalLabel">Billing Actions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Billing calculation is complete. You can now send emails to students or download the billing details as a PDF.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="sendBillEmails()">Send Emails</button>
                <button type="button" class="btn btn-success" onclick="downloadPDF()">Download PDF</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
function calculateBill() {
    $.ajax({
        url: 'calculate_bill.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const billPerDay = response.billPerDay;
                // Store data in the session using localStorage to persist on refresh
                sessionStorage.setItem('students', JSON.stringify(response.students));
                sessionStorage.setItem('billPerDay', billPerDay);

                // Update the table with the new data
                updateTable(response.students, billPerDay);

                // Show the modal after table update
                $('#billModal').modal('show');
            } else {
                alert("Error calculating bill. Please try again.");
            }
        },
        error: function() {
            alert("Error occurred during calculation. Please check your server or try again.");
        }
    });
}
$(document).ready(function() {
    // Check if there is any stored student data in sessionStorage
    const storedStudents = sessionStorage.getItem('students');
    const billPerDay = sessionStorage.getItem('billPerDay');

    if (storedStudents && billPerDay) {
        const students = JSON.parse(storedStudents);
        updateTable(students, parseFloat(billPerDay));
    }
});

function updateTable(students, billPerDay) {
    const tableBody = $("#studentTable tbody");
    tableBody.empty();

    students.forEach((student, index) => {
        const totalBill = (student.days_present * billPerDay).toFixed(2);
        const row = `<tr>
            <td>${index + 1}</td>
            <td>${student.firstName} ${student.lastName}</td>
            <td>${student.days_present}</td>
            <td>₹${billPerDay.toFixed(2)}</td>
            <td>₹${totalBill}</td>
        </tr>`;
        tableBody.append(row);
    });
}

function sendBillEmails() {
    $.ajax({
        url: 'send_bill_emails.php',
        type: 'POST',
        success: function(response) {
            alert("Emails sent successfully!");
        },
        error: function() {
            alert("Error sending emails.");
        }
    });
}
function downloadPDF() {
    // Instead of AJAX, directly redirect to the PHP script that generates the PDF
    window.location.href = 'generate_pdf.php';
}


// Filter table by search
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
