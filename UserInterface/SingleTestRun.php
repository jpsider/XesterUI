<!DOCTYPE html>
<html lang="en">
<?php
	$TestRunId=$_GET['testRun_id'];
?>
<?php
	require_once 'components/header.php';
?>
<body>
    <div class="container" style="margin-left:10px">
    	<div class="row">
			<?php
				require_once 'components/Side_Bar.html';
			?>
			<div class="col-sm-9 col-md-10 col-lg-10 main">
				<h3>XesterUI TestRun Results</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Status</th>
							<th>Result</th>
							<th>Elapsed_Time</th>
							<th>Asserts</th>
							<th>date_modified</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = "select ts.ID, " 
										. "ts.Name, "
										. "ts.TestRun_ID, "
										. "ts.Elapsed_Time, "
										. "ts.Status_ID, "
										. "ts.Result_ID, "
										. "ts.date_modified, "													
										. "ts.Asserts, "																							
										. "s.Status, "
										. "s.HtmlColor, "
										. "r.Name as Result, "
										. "r.HtmlColor as Result_Color "
									. "from TESTSUITES ts "
									. "join x_status s on ts.Status_ID=s.ID "
									. "join X_result r on ts.Result_ID=r.ID "
									. "where ts.TestRun_ID like $TestRunId ";
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr><form action="SingleTestSuite.php" method="get">';
								echo '<td><input type="hidden" name="testSuite_id" value='.$row['ID'].'>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Name'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '>'. $row['Status'] . '</td>';
								echo '<td style=background-color:'. $row['Result_Color'] . '>'. $row['Result'] . '</td>';
								echo '<td>'. $row['Elapsed_Time'] . '</td>';
								echo '<td>'. $row['Asserts'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '<td width=250>';
								echo '<input type="submit" class="btn btn-info" value="View TestCases"></form>';
								echo '</td>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
					</table>
		   		</div>
			</div>
		</div>
	</div> <!-- /container -->
</body>
<?php
	require_once 'components/footer.php';
?>
</html>