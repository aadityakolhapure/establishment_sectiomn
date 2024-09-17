<?php include('../includes/config.php') ?>
<?php include('../includes/session.php') ?>


<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="form.css">
	<link rel="apple-touch-icon" sizes="180x180" href="../vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../vendors/images/favicon-32x32.png">


	<style>
		@media print {
			body *:not(.container):not(.container *) {
				visibility: hidden;
			}

			.container {
				position: absolute;
				margin: 120px;
			}
		}

		.main {
			display: flex;
			flex-direction: row;
			align-items: stretch;
		}

		.main button {
			/* 
			width: 140px;
			height: 30px;
			align-items: center;
			display: flex;
			justify-content: center;
			font-size: 15px;
			cursor: pointer; */
			margin: 10px;
			cursor: pointer;
			background-color: #fdc93b;
			font-size: 16px;
			font-weight: 550;
			padding: 4px 12px;
			border: 2px solid #000;
			border-radius: 5px;
			outline: none;
			margin-left: 20px;
		}

		.main button a {
			text-decoration: solid;
			color: black;
		}
	</style>
	<title>Establishment Section</title>
</head>

<body>
	
	<div class="container">
		<div class="header">
			<div class="logo">
				<img src="logo_w-modified.png" alt="">
			</div>
			<div class="name">
				<div class="a1">
					<h4>Raosaheb Wangde Master Charitable Trust's</h4>
				</div>
				<div class="a1">
					<h1>DNYANSHREE</h1>
				</div>
				<div class="a1">
					<h3>Institute of Engineering & Technology</h3>
				</div>
				<div class="a1">
					<h4>A/P.:Sonavadi-gajawadi,Sajjangad Road,Tal. & Dist.:Satara-415013</h4>
				</div>
				<div class="a1">
					<h4>Maharashtra State.DTE Code : EN6797</h4>
				</div>
			</div>

			<?php
			if (!isset($_GET['edit']) || empty($_GET['edit'])) {
				header('Location: index.php');
			} else {
				$lid = intval($_GET['edit']);
				$sql = "SELECT tblleaves.id as lid, tblemployees.FirstName, tblemployees.LastName, tblemployees.emp_id, tblemployees.Gender, tblemployees.Phonenumber, tblemployees.EmailId, tblemployees.Av_leave, tblemployees.RegDate, tblemployees.Department, tblleaves.LeaveType, tblleaves.ToDate, tblleaves.FromDate, tblleaves.Description, tblleaves.PostingDate, tblleaves.Status, tblleaves.AdminRemark, tblleaves.admin_status,tblleaves.principal_status,tblleaves.principalRemark,tblleaves.principal_remark_date, tblleaves.registra_remarks, tblleaves.AdminRemarkDate, tblleaves.num_days,tblleaves.dateA,tblleaves.existing_loadA,tblleaves.schedule_timeA,tblleaves.classA,tblleaves.alternative_facultyA,tblleaves.date1,tblleaves.existing_load,tblleaves.schedule_time,tblleaves.class,tblleaves.alternative_faculty FROM tblleaves JOIN tblemployees ON tblleaves.empid = tblemployees.emp_id WHERE tblleaves.id = :lid";
				$query = $dbh->prepare($sql);
				$query->bindParam(':lid', $lid, PDO::PARAM_STR);
				$query->execute();
				$results = $query->fetchAll(PDO::FETCH_OBJ);
				$cnt = 1;
				if ($query->rowCount() > 0) {
					foreach ($results as $result) {
			?>
		</div>
		<hr>
		<h4 id="title">Leave Application Form</h4>
		<div class="header2">
			<div class="gree">
				<span>To,</span><br>
				<span>The Principal,</span>
			</div>
			<div class="date">
				<span>Date- <?php echo htmlentities($result->PostingDate); ?></span>
			</div>
		</div>
		<div class="header3">
			<span>Respected Sir,</span><br>
			<span> I am Mr./Mrs./Ms. <?php echo htmlentities($result->FirstName . " " . $result->LastName); ?> applying for the leave. You are requested to kindly
				approve the same.</span>
		</div>

		<div class="header4">
			<table>
				<tr>
					<td>Department: <?php echo htmlentities($result->Department); ?></td>
				</tr>
			</table>
			<table>
				<tr id="duty">
					<td>
						Reason :
						<?php echo htmlentities($result->Description); ?>
				</tr>
			</table>
			<table>
				<td>
					Nos. of days:
					<?php echo htmlentities($result->num_days); ?>
				</td>
				<td>

					From <?php echo htmlentities($result->FromDate); ?> to <?php echo htmlentities($result->ToDate); ?>
				</td>

			</table>
			<table>
				<tr>
					<td>Type of leave(which is applicable):</td>
					<td><?php echo htmlentities($result->LeaveType); ?><style></style>
					</td>
				</tr>
			</table>

			<table>
				<tr>
					<td>Details of alternative Arrangement:(Please attach separate Sheet if required)</td>
				</tr>
			</table>
			<table>
				<tr>
					<td>SR no.</td>
					<td>Date</td>
					<td>Existing Load</td>
					<td>Schedule <br>Time</td>
					<td>Class</td>
					<td>Name of <br>alternative <br>faculty</td>
					<td>Sign <br>of alternative <br>faculty</td>
				</tr>
				<tr>
					<td>1.</td>
					<td><?php echo htmlentities($result->dateA); ?></td>
					<td><?php echo htmlentities($result->existing_loadA); ?></td>
					<td><?php echo htmlentities($result->schedule_timeA); ?></td>
					<td><?php echo htmlentities($result->classA); ?></td>
					<td><?php echo htmlentities($result->alternative_facultyA); ?></td>
					<td></td>
				</tr>
				<tr>
					<td>2.</td>
					<td><?php echo htmlentities($result->date1); ?></td>
					<td><?php echo htmlentities($result->existing_load); ?></td>
					<td><?php echo htmlentities($result->schedule_time); ?></td>
					<td><?php echo htmlentities($result->class); ?></td>
					<td><?php echo htmlentities($result->alternative_faculty); ?></td>
					<td></td>
				</tr>
				<!-- <tr>
					<td>3.</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>4.</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr> -->
			</table>
			<table>
				<td>
					<br><br><br>
					<div class="box">
						<div class="da">
							<span><?php echo htmlentities($result->PostingDate); ?></span>
						</div>
						<div class="si">
							Applicant Sign
						</div>
					</div>
				</td>
				<td>
					<span>Hod Remark:
						<?php $stats = $result->Status; ?>
						<?php
						if ($stats == 1) : ?>
							<span style="color: green;"><?php echo "Approved"; ?></span>
						<?php
						elseif ($stats == 2) : ?>
							<span style="color: red;"><?php echo "Rejected"; ?></span>
						<?php
						else : ?>
							<span style="color: blue;"><?php echo "Pending"; ?></span>
						<?php endif ?>
					</span><br><br><br>
					<div class="box">

						<div class="si" style="justify-item: center">
							HOD Sign
						</div>
					</div>
				</td>
			</table>
			<table>
				<td>
					<span>Establishment Section</span>
				</td>
				<td>
					<span>Remark: <?php echo htmlentities($result->principalRemark); ?></span>
				</td>
			</table>
			<table>
				<tr>
					<td>
						<table>
							<tr>
								<td>Type of leave</td>
								<td>Opening Balance</td>
								<td>Sanction Days</td>
								<td>Balance Days</td>
							</tr>
							<tr>
								<td>1.</td>
								<td></td>
								<td><?php echo htmlentities($result->num_days); ?></td>
								<td><?php echo htmlentities($result->Av_leave); ?></td>
							</tr>

						</table>
					</td>
					<td>
						<span>Approved:
							<?php $stats12 = $result->principal_status; ?>
							<?php
							if ($stats12 == 1) : ?>
								<span style="color: green;"><?php echo "Approved"; ?></span>
							<?php
							elseif ($stats12 == 2) : ?>
								<span style="color: red;"><?php echo "Rejected"; ?></span>
							<?php
							else : ?>
								<span style="color: blue;"><?php echo "Pending"; ?></span>
							<?php endif ?>
						</span><br><br><br>
						<div class="box">
							<div class="da">
								<span>Date: <?php echo htmlentities($result->principal_remark_date); ?></span>
							</div>
							<div class="si">
								Principal
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
<?php $cnt++;
					}
				}
			} ?>


	</div>
	<div class="main">
		<button id="print" onclick="window.print()">Print the Form</button>
		<button id="print"><a href="view_leave.php?edit=<?php echo htmlentities($result->lid); ?>"> back</a></button>
	</div>


	<!-- <script>
		const printDoc = document.getElementById('print');
		printDoc.addEventListener('click', function() {
			print();
		});
	</script> -->

</body>

</html>