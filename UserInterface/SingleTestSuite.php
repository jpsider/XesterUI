<!DOCTYPE html>
<html lang="en">
<?php
	$TestsuiteId=$_GET['testSuite_id'];
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
										. "t.IP_Address "
									. "from TESTCASES tc "
									. "join STATUS s on tc.Status_ID=s.ID "
									. "join RESULTS r on tc.Result_ID=r.ID "
									. "join targets t on tc.Target_ID=t.ID "
									. "where tc.TEST_SUITE_ID like $TestsuiteId ";
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Name'] . '</td>';
								echo '<td>'. $row['Target_Name'] . '</td>';
								echo '<td>'. $row['IP_Address'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '>'. $row['Status'] . '</td>';
								if ($row['Result_ID'] == 1) {
									echo '<td style=background-color:'. $row['Result_Color'] . '>'. $row['Result'] . '</td>';
								} elseif ($row['Result_ID'] == 2) {
									echo '<td><form action="ViewStacktrace.php" method="get"><input type="hidden" name="testcase_id" value='.$row['ID'].'><input type="submit" class="btn btn-danger" value="View StackTrace"></form></td>';
								} else {
									echo '<td style=background-color:'. $row['Result_Color'] . '>'. $row['Result'] . '</td>';
								}
								echo '<td>'. $row['Elapsed_Time'] . '</td>';
								echo '<td>'. $row['Asserts'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '<td width=250>';
								echo '<form action="SubmitTestRun.php" method="get"><input type="hidden" name="testSuite_id" value='.$row['ID'].'><input type="submit" class="btn btn-success" value="Remediate"></form>';
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