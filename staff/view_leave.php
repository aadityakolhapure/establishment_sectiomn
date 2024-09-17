<?php error_reporting(0); ?>
<?php include('includes/header.php') ?>
<?php include('../includes/session.php') ?>

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
						if (!isset($_GET['edit']) && empty($_GET['edit'])) {
							header('Location: index.php');
						} else {

							$lid = intval($_GET['edit']);
							$sql = "SELECT tblleaves.id as lid,tblemployees.FirstName,tblemployees.LastName,tblemployees.emp_id,tblemployees.Gender,tblemployees.Phonenumber,tblemployees.EmailId,tblemployees.Av_leave,tblleaves.LeaveType,tblleaves.ToDate,tblleaves.FromDate,tblleaves.Description,tblleaves.PostingDate,tblleaves.Status,tblleaves.AdminRemark,tblleaves.admin_status,tblleaves.registra_remarks,tblleaves.principal_status,tblleaves.principalRemark,tblleaves.principal_remark_date,tblleaves.AdminRemarkDate,tblleaves.num_days,tblleaves.dateA,tblleaves.existing_loadA,tblleaves.schedule_timeA,tblleaves.classA,tblleaves.alternative_facultyA,tblleaves.date1,tblleaves.existing_load,tblleaves.schedule_time,tblleaves.class,tblleaves.alternative_faculty from tblleaves join tblemployees on tblleaves.empid=tblemployees.emp_id where tblleaves.id=:lid";
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
												<input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="<?php echo htmlentities($result->num_days); ?>">
											</div>
										</div>
										<div class="col-md-4 col-sm-12">
											<div class="form-group">
												<label style="font-size:16px;"><b>Available Balance</b></label>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="<?php echo htmlentities($result->Av_leave); ?>">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label style="font-size:16px;"><b>Leave Period</b></label>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="From <?php echo htmlentities($result->FromDate); ?> to <?php echo htmlentities($result->ToDate); ?>">
											</div>
										</div>

									</div>

									<div class="row">

									</div>
									<div class="form-group row">
										<label style="font-size:16px;" class="col-sm-12 col-md-2 col-form-label"><b>Leave Reason</b></label>
										<div class="col-sm-12 col-md-10">
											<textarea name="" class="form-control text_area" readonly type="text"><?php echo htmlentities($result->Description); ?></textarea>
										</div>
									</div>
									<div class="form-group row">
										<label style="font-size:16px;" class="col-sm-12 col-md-2 col-form-label"><b>HOD Remarks</b></label>
										<div class="col-sm-12 col-md-10">
											<?php
											if ($result->AdminRemark == "") : ?>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Waiting for Approval"; ?>">
											<?php else : ?>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result->AdminRemark); ?>">
											<?php endif ?>
										</div>
									</div>
									<div class="form-group row">
										<label style="font-size:16px;" class="col-sm-12 col-md-2 col-form-label"><b>Principal Remarks</b></label>
										<div class="col-sm-12 col-md-10">
											<?php
											if ($result->principal_remark == "") : ?>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Waiting for Approval"; ?>">
											<?php else : ?>
												<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result->principal_remark); ?>">
											<?php endif ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label style="font-size:16px;"><b>Action Taken Date</b></label>
												<?php
												if ($result->principal_remark_date == "") : ?>
													<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "NA"; ?>">
												<?php else : ?>
													<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result->principal_remark_date); ?>">
												<?php endif ?>

											</div>
										</div>


										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label style="font-size:16px;"><b>HOD status</b></label>
													<?php $stats = $result->Status; ?>
													<?php
													if ($stats == 1) : ?>
														<input type="text" style="color: green;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Recommend"; ?>">
													<?php
													elseif ($stats == 2) : ?>
														<input type="text" style="color: red; font-size: 16px;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Not recommend"; ?>">
													<?php
													else : ?>
														<input type="text" style="color: blue;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Pending"; ?>">
													<?php endif ?>
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label style="font-size:16px;"><b>Admin Status</b></label>
													<?php $stats = $result->admin_status; ?>
													<?php
													if ($stats == 1) : ?>
														<input type="text" style="color: green;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Forwarded"; ?>">
													<?php
													elseif ($stats == 2) : ?>
														<input type="text" style="color: red; font-size: 16px;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Rejected"; ?>">
													<?php
													else : ?>
														<input type="text" style="color: blue;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Pending"; ?>">
													<?php endif ?>
												</div>
											</div>


											<div class="col-md-4">
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

										</div>

										<div class="row" style="display: flex; flex-direction:column; padding-left: 10px">

											<div class="pd-20">
												<h2 class="text-blue h4">Load Balance</h2>
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
									</div>

									<?php
									// Check if principal status is approved
									if ($prin_stats == 1) {
										// Principal status is approved, show the print button
									?>
										<div class="row">
											<button style="height: 30px; width: 80px;">
											<a title="VIEW" href="leave_application.php?edit=<?php echo htmlentities($result->lid); ?>" data-color="#265ed7">
												Print</i>
											</a>
										</button>
										</div>
									<?php
									}
									?>




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