<!DOCTYPE html>
<html lang="en">
<?php
	$testRun_id=$_GET['testRun_id'];
?>
<?php
	require_once 'components/header.php';
?>
<script> 
	$(document).ready(function() {
		$('#example').dataTable( {
			"order":[[ 0, "asc" ]]
		});
	});
</script>
<body>
    <div class="container" style="margin-left:10px">
    	<div class="row">
			<?php
				require_once 'components/Side_Bar.html';
			?>
			<div class="col-sm-9 col-md-10 col-lg-10 main">
				<h3>XesterUI TestCase Results</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Target_Name</th>
							<th>IP_Address</th>
							<th>Status</th>
							<th>Result</th>
							<th>Elapsed_Time</th>
							<th>Asserts</th>
							<th>TestSuiteName</th>
							<th>date_modified</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = "select tc.ID, " 
										. "tc.Name, "
										. "tc.Target_ID, "
										. "tc.TEST_SUITE_ID, "
										. "tc.Elapsed_Time, "
										. "tc.Status_ID, "
										. "tc.Result_ID, "
										. "tc.date_modified, "													
										. "tc.Asserts, "																							
										. "s.Status, "
										. "s.HtmlColor, "
										. "r.Name as Result, "
										. "r.HtmlColor as Result_Color, "
										. "t.Target_Name, "
										. "t.System_ID, "
										. "t.IP_Address, "
										. "ts.Name as TestSuiteName "
									. "from TESTCASES tc "
									. "join STATUS s on tc.Status_ID=s.ID "
									. "join RESULTS r on tc.Result_ID=r.ID "
									. "join targets t on tc.Target_ID=t.ID "
									. "join testsuites ts on tc.TEST_SUITE_ID=ts.ID "
									. "where ts.TestRun_ID like $testRun_id ";
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Name'] . '</td>';
								echo '<td>'. $row['Target_Name'] . '</td>';
								echo '<td>'. $row['IP_Address'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '>'. $row['Status'] . '</td>';
								echo '<td style=background-color:'. $row['Result_Color'] . '>'. $row['Result'] . '</td>';
								echo '<td>'. $row['Elapsed_Time'] . '</td>';
								echo '<td>'. $row['Asserts'] . '</td>';
								echo '<td>'. $row['TestSuiteName'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								if ($row['Result_ID'] == 2) {
									echo '<td style=background-color:'. $row['Result_Color'] . '><form action="ViewStacktrace.php" method="get"><input type="hidden" name="testcase_id" value='.$row['ID'].'><input type="submit" class="btn btn-info" value="View StackTrace"></form>';
									echo '<form action="systems.php" method="get"><input type="hidden" name="System_ID" value='.$row['System_ID'].'><input type="submit" class="btn btn-success" value="Remediate"></form></td>';
								} else {
									echo '<td><input type="submit" class="btn btn-success" value="ReRun"></form></td>';
								}								
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