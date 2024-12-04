<?php
session_start();
include('includes/dbconn.php');

if (isset($_POST['login'])) {
    $email_or_username = $_POST['email_or_username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Prepare query based on the selected role
    if ($role == 'admin') {
        $stmt = $mysqli->prepare("SELECT username, email, password, id, hostel_name FROM admin WHERE (username = ? OR email = ?)");
        $stmt->bind_param('ss', $email_or_username, $email_or_username);
    } elseif ($role == 'student') {
        $stmt = $mysqli->prepare("SELECT enrollment_no, password, id FROM userregistration WHERE enrollment_no = ?");
        $stmt->bind_param('s', $email_or_username);
    } elseif ($role == 'staff') {
        $stmt = $mysqli->prepare("SELECT id, staff_name, role, email, password FROM staff WHERE email = ?");
        $stmt->bind_param('s', $email_or_username);
    } elseif ($role == 'mess_committee') {
        $stmt = $mysqli->prepare("SELECT id, committee_member_name, email, password FROM messcommittee WHERE email = ?");
        $stmt->bind_param('s', $email_or_username);
    } else {
        die("Invalid role selected.");
    }

    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "<script>alert('Invalid credentials. Please try again.'); window.location.href = 'index.php';</script>";
        $stmt->close();
        exit(); // Stop further execution after redirection
    }

    if ($role == 'admin') {
        $stmt->bind_result($db_username, $db_email, $db_password, $id, $hostel_name);
    } elseif ($role == 'student') {
        $stmt->bind_result($db_enrollment_no, $db_password, $id);
    } elseif ($role == 'staff') {
        $stmt->bind_result($id, $staff_name, $db_role, $db_email, $db_password);
    } elseif ($role == 'mess_committee') {
        $stmt->bind_result($id, $committee_member_name, $db_email, $db_password);
    }

    $stmt->fetch();
    $stmt->close();

    // Verify password based on hash type
    if (strlen($db_password) == 32 && md5($password) == $db_password) {
        // Update to password_hash if using an old md5 password
        $new_hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_stmt = $mysqli->prepare("UPDATE messcommittee SET password = ? WHERE email = ?");
        $update_stmt->bind_param('ss', $new_hashed_password, $email_or_username);
        $update_stmt->execute();
        $update_stmt->close();
    } elseif (!password_verify($password, $db_password)) {
        echo "<script>alert('Invalid credentials. Please try again.'); window.location.href = 'index.php';</script>";
        exit(); // Stop further execution after redirection
    }

    // Set session variables and redirect based on role
    $_SESSION['id'] = $id;
    $_SESSION['login'] = $email_or_username;
    $_SESSION['role'] = $role;

    if ($role == 'admin') {
        $_SESSION['hostel_name'] = $hostel_name;
        header("location:admin/dashboard.php");
    } elseif ($role == 'student') {
        header("location:student/dashboard.php");
    } elseif ($role == 'staff') {
        $_SESSION['staff_name'] = $staff_name;
        $_SESSION['staff_role'] = $db_role;
        header("location:administration/dashboard.php");
    } elseif ($role == 'mess_committee') {
        $_SESSION['committee_member_name'] = $committee_member_name;
        header("location:messcommittee/dashboard.php");
    }
    exit(); // Stop further execution after redirection
}
?>

<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>Hostel Management System</title>
    <link href="dist/css/style.min.css" rel="stylesheet">
    <script>
        function updateLabelsAndPlaceholders() {
            var role = document.getElementById("role").value;
            var emailOrUsernameLabel = document.getElementById("email_or_username_label");
            var passwordLabel = document.getElementById("password_label");
            var emailOrUsernameInput = document.getElementById("email_or_username");
            var passwordInput = document.getElementById("pwd");

            switch (role) {
                case 'admin':
                    emailOrUsernameLabel.innerText = "Username or Email";
                    emailOrUsernameInput.placeholder = "Enter your username or email";
                    passwordLabel.innerText = "Password";
                    passwordInput.placeholder = "Enter your password";
                    break;
                case 'student':
                    emailOrUsernameLabel.innerText = "Enrollment Number";
                    emailOrUsernameInput.placeholder = "Enter your enrollment number";
                    passwordLabel.innerText = "Password";
                    passwordInput.placeholder = "Enter your password";
                    break;
                case 'mess_committee':
                    emailOrUsernameLabel.innerText = "Email";
                    emailOrUsernameInput.placeholder = "Enter your email";
                    passwordLabel.innerText = "Password";
                    passwordInput.placeholder = "Enter your password";
                    break;
                case 'staff':
                    emailOrUsernameLabel.innerText = "Email";
                    emailOrUsernameInput.placeholder = "Enter your email";
                    passwordLabel.innerText = "Password";
                    passwordInput.placeholder = "Enter your password";
                    break;
                default:
                    emailOrUsernameLabel.innerText = "Email or Username";
                    emailOrUsernameInput.placeholder = "Enter your email or username";
                    passwordLabel.innerText = "Password";
                    passwordInput.placeholder = "Enter your password";
            }
        }
    </script>
</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
            style="background:url(assets/images/big/auth-bg.jp) no-repeat center center;">
            <div class="auth-box row">
                <div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url(assets/images/adimg.png);">
                </div>
                <div class="col-lg-5 col-md-7 bg-white">
                    <div class="p-3">
                        <h2 class="mt-3 text-center">Login</h2>
                        <form class="mt-4" method="POST">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="role">Select Role</label>
                                        <select class="form-control" name="role" id="role" required onchange="updateLabelsAndPlaceholders()">
                                            <option value="">Select Role</option>
                                            <option value="admin">Admin</option>
                                            <option value="student">Student</option>
                                            <option value="mess_committee">Mess Committee</option>
                                            <option value="staff">Staff</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" id="email_or_username_label" for="email_or_username">Enrollment Number</label>
                                        <input class="form-control" name="email_or_username" id="email_or_username" type="text"
                                            placeholder="Enter your enrollment number" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" id="password_label" for="pwd">Password</label>
                                        <input class="form-control" name="password" id="pwd" type="password"
                                            placeholder="Enter your password" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" name="login" class="btn btn-block btn-dark">LOGIN</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
        $(".preloader").fadeOut();
    </script>
</body>

</html>
