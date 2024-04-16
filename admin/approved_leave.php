<?php include('includes/header.php') ?>
<?php include('../includes/session.php') ?>

<body>
	<div class="pre-loader">
		<div class="pre-loader-box">
			<div class="loader-logo"><img src="../vendors/images/" alt=""></div>
			<div class='loader-progress' id="progress_div">
				<div class='bar' id='bar1'></div>
			</div>
			<div class='percent' id='percent1'>0%</div>
			<div class="loading-text">
				Loading...
			</div>
		</div>
	</div>

	<?php include('includes/navbar.php') ?>
	<?php include('includes/right_sidebar.php') ?>
	<?php include('includes/left_sidebar.php') ?>

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
								<li class="breadcrumb-item active" aria-current="page">Approved Leave</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>

			<div class="card-box mb-30">
				<div class="pd-20">
					<h2 class="text-blue h4">APPROVED LEAVE</h2>
				</div>
				<div class="col-md-5">
					<input type="text" id="searchInput2" class="form-control" placeholder="Search....">
				</div>
				<div class="pb-20">
					<table class="data-table table stripe hover nowrap" id="table28">
						<thead>
							<tr>
								<th class="table-plus datatable-nosort">STAFF NAME</th>
								<th>LEAVE TYPE</th>
								<th>APPLIED DATE</th>
								<th>HOD STATUS</th>
								<th>ADMIN STATUS</th>
								<th>PRINCIPAL STATUS</th>
								<th class="datatable-nosort">ACTION</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$status = 1; // Approved status
							$sql = "SELECT tblleaves.id as lid, tblemployees.FirstName, tblemployees.LastName, tblemployees.location, tblemployees.emp_id, tblleaves.LeaveType, tblleaves.PostingDate, tblleaves.Status, tblleaves.admin_status, tblleaves.principal_status 
            FROM tblleaves 
            JOIN tblemployees ON tblleaves.empid = tblemployees.emp_id
            WHERE tblleaves.Status = '$status' AND tblleaves.admin_status = '$status' AND tblleaves.principal_status = '$status'";
							$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));

							if (mysqli_num_rows($query) > 0) {
								while ($row = mysqli_fetch_assoc($query)) {
									echo "<tr>";
									echo "<td class='table-plus'>";
									echo "<div class='name-avatar d-flex align-items-center'>";
									echo "<div class='avatar mr-2 flex-shrink-0'>";
									echo "<img src='" . (!empty($row['location']) ? '../uploads/' . $row['location'] : '../uploads/NO-IMAGE-AVAILABLE.jpg') . "' class='border-radius-100 shadow' width='40' height='40' alt=''>";
									echo "</div>";
									echo "<div class='txt'>";
									echo "<div class='weight-600'>" . $row['FirstName'] . " " . $row['LastName'] . "</div>";
									echo "</div>";
									echo "</div>";
									echo "</td>";
									echo "<td>" . $row['LeaveType'] . "</td>";
									echo "<td>" . $row['PostingDate'] . "</td>";
									echo "<td>";
									if ($row['Status'] == 1) {
										echo "<span class='text-success'>Recommend</span>";
									} elseif ($row['Status'] == 2) {
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
									echo "<td>";
									echo "<div class='table-actions'>";
									echo "<a title='VIEW' href='leave_details.php?leaveid=" . $row['lid'] . "'><i class='dw dw-eye' data-color='#265ed7'></i></a>";
									echo "</div>";
									echo "</td>";
									echo "</tr>";
								}
							} else {
								echo "<tr><td colspan='7'>No approved leave history found.</td></tr>";
							}
							?>
						</tbody>
					</table>
				</div>
			</div>

			<?php include('includes/footer.php'); ?>
		</div>
	</div>

	<!-- js -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$(document).ready(function() {
			$("#searchInput2").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#table28 tbody tr").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
		});
	</script>
	<?php include('includes/scripts.php') ?>
</body>

</html>