<?php error_reporting(0); ?>
<?php include('includes/header.php') ?>
<?php include('../includes/session.php') ?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';
// code for update the read notification status
$isread = 2;
$did = intval($_GET['leaveid']);
date_default_timezone_set('Asia/Kolkata');
$admremarkdate = date('Y-m-d G:i:s ', strtotime("now"));
$sql = "update tblleaves set IsRead=:isread where id=:did";
$query = $dbh->prepare($sql);
$query->bindParam(':isread', $isread, PDO::PARAM_STR);
$query->bindParam(':did', $did, PDO::PARAM_STR);
$query->execute();

// code for action taken on leave
if (isset($_POST['update'])) {
	$did = intval($_GET['leaveid']);
	$description = $_POST['description'];
	$status = $_POST['status'];
	$av_leave = $_POST['av_leave'];
	$num_days = $_POST['num_days'];
	$sql = "SELECT * FROM tblleaves WHERE empid = (SELECT empid FROM tblleaves WHERE id = :did)";
	$query = $dbh->prepare($sql);
	$query->bindParam(':did', $did, PDO::PARAM_STR);
	$query->execute();
	$leaveHistory = $query->fetchAll(PDO::FETCH_OBJ);

	// Format leave history
	$leaveHistoryContent = "";
	foreach ($leaveHistory as $leave) {
		$leaveHistoryContent .= "Leave Type: " . htmlentities($leave->LeaveType) . "\n";
		$leaveHistoryContent .= "From: " . htmlentities($leave->FromDate) . " To: " . htmlentities($leave->ToDate) . "\n";
		$leaveHistoryContent .= "Description: " . htmlentities($leave->Description) . "\n";
	}

	$principalQuery = mysqli_query($conn, "SELECT EmailId FROM tblemployees WHERE Role = 'Principal'");
	$principalRow = mysqli_fetch_assoc($principalQuery);
	$principalEmail = $principalRow['EmailId'];

	$sql = "SELECT FirstName, LastName FROM tblemployees WHERE emp_id = (SELECT empid FROM tblleaves WHERE id = :did)";
	$query = $dbh->prepare($sql);
	$query->bindParam(':did', $did, PDO::PARAM_STR);
	$query->execute();
	$employeeResult = $query->fetch(PDO::FETCH_ASSOC);
	$employeeName = $employeeResult['FirstName'] . ' ' . $employeeResult['LastName'];


	date_default_timezone_set('Asia/Kolkata');
	$admremarkdate = date('Y-m-d G:i:s ', strtotime("now"));

	if ($status === '2') {
		$sql = "update tblleaves set registra_remarks=:description,admin_status=:status,principal_status=:status,AdminRemarkDate=:admremarkdate where id=:did";

		$query = $dbh->prepare($sql);
		$query->bindParam(':description', $description, PDO::PARAM_STR);
		$query->bindParam(':status', $status, PDO::PARAM_STR);
		$query->bindParam(':admremarkdate', $admremarkdate, PDO::PARAM_STR);
		$query->bindParam(':did', $did, PDO::PARAM_STR);
		$query->execute();
		echo "<script>alert('Leave updated Successfully');</script>";
	} elseif ($status === '1') {
		$result = mysqli_query($conn, "update tblleaves, tblemployees set tblleaves.registra_remarks='$description',tblleaves.admin_status='$status',tblleaves.AdminRemarkDate='$admremarkdate' where tblleaves.empid = tblemployees.emp_id AND tblleaves.id='$did'");

		if ($result) {
			// echo "<script>alert('Leave updated Successfully');</script>";


			//Create an instance; passing `true` enables exceptions
			$mail = new PHPMailer(true);

			//Server settings
			$mail->SMTPDebug = SMTP::DEBUG_SERVER;
			$mail->SMTPDebug = 0; //Enable verbose debug output
			$mail->isSMTP(); //Send using SMTP
			$mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
			$mail->SMTPAuth = true; //Enable SMTP authentication
			$mail->Username = 'aadityakolhapure28@gmail.com'; //SMTP username
			$mail->Password = 'rsyiovsdcybbxmjy'; //SMTP password  
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
			$mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			//Recipients
			$mail->setFrom('aadityakolhapure28@gmail.com', 'Leave Management System');
			$mail->addAddress($principalEmail, 'principal'); //Add HOD's email as recipient
			$mail->addReplyTo('aadityakolhapure28@gmail.com', 'Leave Management System');

			//Content
			$mail->isHTML(true); //Set email format to HTML
			$mail->Subject = 'Leave Application Notification';
			$mail->Body = "A leave application has been inspected by the administrative for " . $employeeName . ". Please review it.\n\nLeave History:\n" . $leaveHistoryContent;
			if ($mail->send()) {
				echo "<script>alert('Leave Application was successful. Email sent to Principal.');</script>";
			} else {
				echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		} else {
			die(mysqli_error());
		}
	}
}

// date_default_timezone_set('Asia/Kolkata');
// $admremarkdate=date('Y-m-d G:i:s ', strtotime("now"));

// $sql="update tblleaves set AdminRemark=:description,Status=:status,AdminRemarkDate=:admremarkdate where id=:did";

// $query = $dbh->prepare($sql);
// $query->bindParam(':description',$description,PDO::PARAM_STR);
// $query->bindParam(':status',$status,PDO::PARAM_STR);
// $query->bindParam(':admremarkdate',$admremarkdate,PDO::PARAM_STR);
// $query->bindParam(':did',$did,PDO::PARAM_STR);
// $query->execute();
// echo "<script>alert('Leave updated Successfully');</script>";

?>

<style>
	input[type="text"] {
		font-size: 16px;
		color: #0f0d1b;
		font-family: Verdana, Helvetica;
	}

	.btn-outline:hover {
		color: #fff;
		background-color: #524d7d;
		border-color: #524d7d;
	}

	textarea {
		font-size: 16px;
		color: #0f0d1b;
		font-family: Verdana, Helvetica;
	}

	textarea.text_area {
		height: 8em;
		font-size: 16px;
		color: #0f0d1b;
		font-family: Verdana, Helvetica;
	}
</style>

<body>
	<!-- <div class="pre-loader">
		<div class="pre-loader-box">
			<div class="loader-logo"><img src="../vendors/images/favicon-32x32.png" alt="" style="height: 100px; width: 100px;"></div>
			<div class='loader-progress' id="progress_div">
				<div class='bar' id='bar1'></div>
			</div>
			<div class='percent' id='percent1'>0%</div>
			<div class="loading-text">
				Loading...
			</div>
		</div>
	</div> -->

	<?php include('includes/navbar.php') ?>

	<?php include('includes/right_sidebar.php') ?>

	<?php include('includes/left_sidebar.php') ?>

	<div class="mobile-menu-overlay"></div>

	<div class="main-container">
		<div class="pd-ltr-20">
			<div class="min-height-200px">
				<div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>LEAVE DETAILS</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Leave</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>

				<div class="pd-20 card-box mb-30">
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4">Leave Details</h4>
							<p class="mb-20"></p>
						</div>
					</div>
					<form method="post" action="">

						<?php
						if (!isset($_GET['leaveid']) && empty($_GET['leaveid'])) {
							header('Location: admin_dashboard.php');
						} else {

							$lid = intval($_GET['leaveid']);
							$sql = "SELECT tblleaves.id as lid,tblemployees.FirstName,tblemployees.LastName,tblemployees.emp_id,tblemployees.Gender,tblemployees.Phonenumber,tblemployees.EmailId,tblemployees.Av_leave,tblleaves.LeaveType,tblleaves.ToDate,tblleaves.FromDate,tblleaves.Description,tblleaves.PostingDate,tblleaves.Status,tblleaves.AdminRemark,tblleaves.admin_status,tblleaves.principal_status,tblleaves.registra_remarks,tblleaves.AdminRemarkDate,tblleaves.num_days,tblleaves.dateA,tblleaves.existing_loadA,tblleaves.schedule_timeA,tblleaves.classA,tblleaves.alternative_facultyA,tblleaves.date1,tblleaves.existing_load,tblleaves.schedule_time,tblleaves.class,tblleaves.alternative_faculty from tblleaves join tblemployees on tblleaves.empid=tblemployees.emp_id where tblleaves.id=:lid";
							$query = $dbh->prepare($sql);
							$query->bindParam(':lid', $lid, PDO::PARAM_STR);
							$query->execute();
							$results = $query->fetchAll(PDO::FETCH_OBJ);
							$cnt = 1;
							if ($query->rowCount() > 0) {
								foreach ($results as $result) {
						?>

									<div class="row">
										<div class="col-md-4 col-sm-12">
											<div class="form-group">
												<label style="font-size:16px;"><b>Full Name</b></label>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result->FirstName . " " . $result->LastName); ?>">
											</div>
										</div>
										<div class="col-md-4 col-sm-12">
											<div class="form-group">
												<label style="font-size:16px;"><b>Email Address</b></label>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="<?php echo htmlentities($result->EmailId); ?>">
											</div>
										</div>
										<div class="col-md-4 col-sm-12">
											<div class="form-group">
												<label style="font-size:16px;"><b>Gender</b></label>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-success" readonly value="<?php echo htmlentities($result->Gender); ?>">
											</div>
										</div>
										<div class="col-md-4 col-sm-12">
											<div class="form-group">
												<label style="font-size:16px;"><b>Phone Number</b></label>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result->Phonenumber); ?>">
											</div>
										</div>
										<div class="col-md-4 col-sm-12">
											<div class="form-group">
												<label style="font-size:16px;"><b>Leave Type</b></label>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="<?php echo htmlentities($result->LeaveType); ?>">
											</div>
										</div>
										<div class="col-md-4 col-sm-12">
											<div class="form-group">
												<label style="font-size:16px;"><b>Applied Date</b></label>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-success" readonly value="<?php echo htmlentities($result->PostingDate); ?>">
											</div>
										</div>

										<div class="col-md-4 col-sm-12">
											<div class="form-group">
												<label style="font-size:16px;"><b>Applied No. of Days</b></label>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly name="num_days" value="<?php echo htmlentities($result->num_days); ?>">
											</div>
										</div>
										<div class="col-md-4 col-sm-12">
											<div class="form-group">
												<label style="font-size:16px;"><b>Available No. of Days</b></label>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly name="av_leave" value="<?php echo htmlentities($result->Av_leave); ?>">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label style="font-size:16px;"><b>Leave Period</b></label>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="From <?php echo htmlentities($result->FromDate); ?> to <?php echo htmlentities($result->ToDate); ?>">
											</div>
										</div>

									</div>
									<div class="form-group row">
										<label style="font-size:16px;" class="col-sm-12 col-md-2 col-form-label"><b>Leave Reason</b></label>
										<div class="col-sm-12 col-md-10">
											<textarea name="" class="form-control text_area" readonly type="text"><?php echo htmlentities($result->Description); ?></textarea>
										</div>
									</div>
									<div class="form-group row">
										<label style="font-size:16px;" class="col-sm-12 col-md-2 col-form-label"><b>Waiting Remarks</b></label>
										<div class="col-sm-12 col-md-10">
											<?php
											if ($result->AdminRemark == "") : ?>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Waiting for Approval"; ?>">
											<?php else : ?>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Action Taken"; ?>">
											<?php endif ?>
										</div>
									</div>
									<div class="form-group row">
										<label style="font-size:16px;" class="col-sm-12 col-md-2 col-form-label"><b>Admin Remarks</b></label>
										<div class="col-sm-12 col-md-10">
											<?php
											if ($result->registra_remarks == "") : ?>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Waiting for Approval"; ?>">
											<?php else : ?>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result->registra_remarks); ?>">
											<?php endif ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label style="font-size:16px;"><b>Action Taken Date</b></label>
												<?php
												if ($result->AdminRemarkDate == "") : ?>
													<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "NA"; ?>">
												<?php else : ?>
													<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result->AdminRemarkDate); ?>">
												<?php endif ?>

											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label style="font-size:16px;"><b>HOD Status</b></label>
												<?php $stats = $result->Status; ?>
												<?php
												if ($stats == 1) : ?>
													<input type="text" style="color: green;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Recommended"; ?>">
												<?php
												elseif ($stats == 2) : ?>
													<input type="text" style="color: red; font-size: 16px;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Not Recommended"; ?>">
												<?php
												else : ?>
													<input type="text" style="color: blue;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Pending"; ?>">
												<?php endif ?>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label style="font-size:16px;"><b>Admin Status</b></label>
												<?php $ad_stats = $result->admin_status; ?>
												<?php
												if ($ad_stats == 1) : ?>
													<input type="text" style="color: green;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Forwarded"; ?>">
												<?php
												elseif ($ad_stats == 2) : ?>
													<input type="text" style="color: red; font-size: 16px;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Rejected"; ?>">
												<?php
												else : ?>
													<input type="text" style="color: blue;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Pending"; ?>">
												<?php endif ?>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label style="font-size:16px;"><b>Principal Status</b></label>
												<?php $prin_stats = $result->principal_status; ?>
												<?php
												if ($prin_stats == 1) : ?>
													<input type="text" style="color: green;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Approved"; ?>">
												<?php
												elseif ($prin_stats == 2) : ?>
													<input type="text" style="color: red; font-size: 16px;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Rejected"; ?>">
												<?php
												else : ?>
													<input type="text" style="color: blue;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Pending"; ?>">
												<?php endif ?>
											</div>
										</div>
										<div class="row" style="display: flex; flex-direction:column; padding-left: 10px">

											<div class="pd-20">
												<h2 class="text-blue h4">Load Adjustment</h2>
											</div>
											<div class="pb-10">
												<table class="data-table table">
													<thead>
														<tr>
															<th class="table-plus">Load Date</th>
															<th>Existing load</th>
															<th>Schedule Time</th>
															<th>Class</th>
															<th>Alternative Faculty</th>

														</tr>
													</thead>
													<tbody>
														<tr>
															<td><?php echo htmlentities($result->dateA); ?></td>
															<td><?php echo htmlentities($result->existing_loadA); ?></td>
															<td><?php echo htmlentities($result->schedule_timeA); ?></td>
															<td><?php echo htmlentities($result->classA); ?></td>
															<td><?php echo htmlentities($result->alternative_facultyA); ?></td>

														</tr>
														<tr>
															<td><?php echo htmlentities($result->date1); ?></td>
															<td><?php echo htmlentities($result->existing_load); ?></td>
															<td><?php echo htmlentities($result->schedule_time); ?></td>
															<td><?php echo htmlentities($result->class); ?></td>
															<td><?php echo htmlentities($result->alternative_faculty); ?></td>

														</tr>


													</tbody>
												</table>
											</div>

										</div>

										<?php
										if (($stats == 1 and $ad_stats == 0 and $prin_stats == 0)) {

										?>
											<div class="col-md-3">
												<div class="form-group">
													<label style="font-size:16px;"><b></b></label>
													<div class="modal-footer justify-content-center">
														<button class="btn btn-primary" id="action_take" data-toggle="modal" data-target="#success-modal">Take&nbsp;Action</button>
													</div>
												</div>
											</div>

											<form name="adminaction" method="post">
												<div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
													<div class="modal-dialog modal-dialog-centered" role="document">
														<div class="modal-content">
															<div class="modal-body text-center font-18">
																<h4 class="mb-20">Leave take action</h4>
																<select name="status" required class="custom-select form-control">
																	<option value="">Choose your option</option>
																	<option value="1">Forward</option>
																	<option value="2">Rejected</option>
																</select>

																<div class="form-group">
																	<label></label>
																	<textarea id="textarea1" name="description" class="form-control" required placeholder="Description" length="300" maxlength="300"></textarea>
																</div>
															</div>
															<div class="modal-footer justify-content-center">
																<input type="submit" class="btn btn-primary" name="update" value="Submit">
															</div>
														</div>
													</div>
												</div>
											</form>

										<?php } ?>
									</div>

						<?php $cnt++;
								}
							}
						} ?>
					</form>
				</div>

			</div>

			<?php include('includes/footer.php'); ?>
		</div>
	</div>
	<!-- js -->

	<?php include('includes/scripts.php') ?>
</body>

</html>