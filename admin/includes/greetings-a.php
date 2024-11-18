<?php 
    // Set timezone to India
    date_default_timezone_set('Asia/Kolkata');
    
    // Define the welcome string based on the time
    $welcome_string = "Welcome"; 
    $numeric_date = date("G"); 
    
    // Conditional greetings based on the time of day
    if ($numeric_date >= 0 && $numeric_date <= 11) {
        $welcome_string = "Good Morning,";
    } else if ($numeric_date >= 12 && $numeric_date <= 15) {
        $welcome_string = "Good Afternoon,";
    } else if ($numeric_date >= 16 && $numeric_date <= 21) {
        $welcome_string = "Good Evening,";
    } else if ($numeric_date >= 22 && $numeric_date <= 24) {
        $welcome_string = "Good Night,";
    }

    // Fetch the admin's username from the database
    $aid = $_SESSION['id'];
    $ret = "SELECT * FROM admin WHERE id = ?";
    $stmt = $mysqli->prepare($ret);
    $stmt->bind_param('i', $aid);
    $stmt->execute();
    $res = $stmt->get_result();
    
    $admin_username = ""; // Initialize variable for admin's username
    while ($row = $res->fetch_object()) {
        $admin_username = $row->username;
    }

    // Fetch the hostel name from the hostels table (id = 1)
    $hostel_ret = "SELECT hostel_name FROM hostels WHERE id = 1";
    $hostel_stmt = $mysqli->prepare($hostel_ret);
    $hostel_stmt->execute();
    $hostel_res = $hostel_stmt->get_result();
    $hostel_name = "";
    
    while ($hostel_row = $hostel_res->fetch_object()) {
        $hostel_name = $hostel_row->hostel_name;
    }

    // Display the welcome message with the hostel name on the next line
    echo "<h3 class='page-title text-truncate text-dark font-weight-medium mb-1'>$welcome_string $admin_username!<br><br><center>$hostel_name.</center></h3>";
?>
