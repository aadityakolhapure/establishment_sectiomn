<?php
session_start();
include('includes/config.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

function logError($message) {
    error_log(date('[Y-m-d H:i:s] ') . "Login Error: " . $message . "\n", 3, 'login_errors.log');
}

if (isset($_POST['signin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    logError("Login attempt for username: " . $username);

    if (!$conn) {
        logError("Database connection failed: " . mysqli_connect_error());
        die("Database connection failed. Please try again later.");
    }

    $sql = "SELECT * FROM tblemployees WHERE EmailId = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt === false) {
        logError("Prepare failed: " . mysqli_error($conn));
        die("Database error. Please try again later.");
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    $execute_result = mysqli_stmt_execute($stmt);

    if ($execute_result === false) {
        logError("Execute failed: " . mysqli_stmt_error($stmt));
        die("Database error. Please try again later.");
    }

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        logError("User found in database");
        
        if (password_verify($password, $row['Password']) || md5($password) === $row['Password']) {
            logError("Password verified successfully");
            $_SESSION['alogin'] = $row['emp_id'];
            $_SESSION['arole'] = $row['Department'];

            $redirect_url = '';
            switch ($row['role']) {
                case 'Admin':
                    $redirect_url = 'admin/admin_dashboard.php';
                    break;
                case 'Staff':
                    $redirect_url = 'staff/index.php';
                    break;
                case 'Principal':
                    $redirect_url = 'principal/index.php';
                    break;
                default:
                    $redirect_url = 'heads/index.php';
                    break;
            }

            logError("Redirecting to: " . $redirect_url);
            echo "<script type='text/javascript'>
                    window.location.href = '$redirect_url';
                  </script>";
            exit();
        } else {
            logError("Password verification failed");
            echo "<script>alert('Invalid password');</script>";
        }
    } else {
        logError("No user found with email: " . $username);
        echo "<script>alert('Invalid username');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>Establishment Section</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>
	<style>
		.brand-logo a .svg,
		.brand-logo a img {
			max-width: 1800px;
			display: block;
			height: auto;
			margin-left: -23px;
			margin-top: 13px;
		}
	</style>
</head>

<body class="login-page">
	<div class="login-header box-shadow" style="height: 85px;">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<a href="index.php">
					<img src="vendors/images/login_logo.png" alt="" style="width: 516px; height: 98px">
				</a>
			</div>
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<img src="vendors/images/login-page-img.png" alt="">
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Welcome To Establishment Section</h2>
						</div>
						<form name="signin" method="post" action="login.php">

							<div class="input-group custom">
								<input type="text" class="form-control form-control-lg" placeholder="Email ID" name="username" id="username">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy fa fa-envelope-o" aria-hidden="true"></i></span>
								</div>
							</div>
							<div class="input-group custom">
								<input type="password" class="form-control form-control-lg" placeholder="**********" name="password" id="password">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>
							<div class="row pb-30">
								<div class="col-6">
									<div class="forgot-password"><a href="forget_password.php">Forgot Password</a></div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<input class="btn btn-primary btn-lg btn-block" name="signin" id="signin" type="submit" value="Sign In">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
</body>

</html>