<?php
include('includes/header.php');
include('../includes/session.php');
?>
<!-- <link rel="stylesheet" href="../js/css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/css/datatables.min.css">
    <link rel="stylesheet" href="../js/css/style.css"> -->

<body>
	<div class="pre-loader">
		<div class="pre-loader-box">
			<div class="loader-logo"><img src="../vendors/images/deskapp-logo-svg.png" alt=""></div>
			<div class='loader-progress' id="progress_div">
				<div class='bar' id='bar1'></div>
			</div>
			<div class='percent' id='percent1'>0%</div>
			<div class="loading-text">
				Loading...
			</div>
		</div>
	</div>



	<?php include('includes/navbar.php'); ?>
	<?php include('includes/right_sidebar.php'); ?>
	<?php include('includes/left_sidebar.php'); ?>

	<div class="mobile-menu-overlay"></div>

	<div class="main-container">
		<div class="pd-ltr-20">
			<div class="page-header">
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="title">
							<h4>Leave Portal</h4>
						</div>
						<nav aria-label="breadcrumb" role="navigation">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
								<li class="breadcrumb-item active" aria-current="page">All Leave</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>

			<div class="card-box mb-30">
				<div class="pd-20">
					<h2 class="text-blue h4">LEAVE HISTORY</h2>
				</div>
				<div class="col-md-5">
					<input type="text" id="searchInput2" class="form-control" placeholder="Search....">
				</div>
				<div class="pb-20">
					<table class="data-table table stripe hover nowrap" id="example">
						<thead>
							<tr>
								<th>Staff Name</th>
								<th>Leave Type</th>
								<th>Applied Date</th>
								<th>HOD Status</th>
								<th>Admin Status</th>
								<th>Principal Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT tblleaves.id as leave_id, tblemployees.FirstName, tblemployees.LastName, tblleaves.LeaveType, tblleaves.PostingDate, tblleaves.Status as hod_status, tblleaves.admin_status, tblleaves.principal_status FROM tblleaves INNER JOIN tblemployees ON tblleaves.empid = tblemployees.emp_id";
							$result = mysqli_query($conn, $sql);

							if (mysqli_num_rows($result) > 0) {
								while ($row = mysqli_fetch_assoc($result)) {
									echo "<tr>";
									echo "<td>" . $row['FirstName'] . " " . $row['LastName'] . "</td>";
									echo "<td>" . $row['LeaveType'] . "</td>";
									echo "<td>" . $row['PostingDate'] . "</td>";
									echo "<td>";
									if ($row['hod_status'] == 1) {
										echo "<span class='text-success'>Recommend</span>";
									} elseif ($row['hod_status'] == 2) {
										echo "<span class='text-danger'>Not Recommend</span>";
									} else {
										echo "<span class='text-warning'>Pending</span>";
									}
									echo "</td>";
									echo "<td>";
									if ($row['admin_status'] == 1) {
										echo "<span class='text-success'>Forward</span>";
									} elseif ($row['admin_status'] == 2) {
										echo "<span class='text-danger'>Rejected</span>";
									} else {
										echo "<span class='text-warning'>Pending</span>";
									}
									echo "</td>";
									echo "<td>";
									if ($row['principal_status'] == 1) {
										echo "<span class='text-success'>Approved</span>";
									} elseif ($row['principal_status'] == 2) {
										echo "<span class='text-danger'>Rejected</span>";
									} else {
										echo "<span class='text-warning'>Pending</span>";
									}
									echo "</td>";
									echo "<td><a class='dropdown-item' href='leave_details.php?leaveid=" . $row['leave_id'] . "'><i class='dw dw-eye'></i> View</a></td>";
									echo "</tr>";
								}
							} else {
								echo "<tr><td colspan='7'>No leave history found.</td></tr>";
							}
							?>
						</tbody>
					</table>
				</div>
			</div>

			<?php include('includes/footer.php'); ?>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$(document).ready(function() {
			$("#searchInput2").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#example tbody tr").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
		});
	</script>
	<?php include('includes/scripts.php') ?>
</body>

</html>