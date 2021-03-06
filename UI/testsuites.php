<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
    <h3>TestRun - TestSuite Results</h3>
				<div class="row">
					<table id="example" class="table table-compact">
						<thead>
							<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Status</th>
							<th>Result</th>
							<th>Elapsed_Time</th>
							<th>TestCase_Count</th>
							<th>Asserts</th>
							<th>date_modified</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
                            include 'components/database.php';
                            if (!empty($_GET['testRun_id'])) {
                                $testRun_id=$_GET['testRun_id'];
                            } else {
                                $testRun_id="%%";
                            }

							$pdo = Database::connect();
							$sql = "select ts.ID, " 
										. "ts.Name, "
										. "ts.TestRun_ID, "
										. "ts.Elapsed_Time, "
										. "ts.Status_ID, "
										. "ts.Result_ID, "
										. "ts.TestCase_Count, "													
										. "ts.date_modified, "													
										. "ts.Asserts, "																							
										. "s.Status, "
										. "s.HtmlColor, "
										. "r.Name as Result, "
										. "r.HtmlColor as Result_Color "
									. "from TESTSUITES ts "
									. "join STATUS s on ts.Status_ID=s.ID "
									. "join RESULTS r on ts.Result_ID=r.ID "
									. "where ts.TestRun_ID like '%$testRun_id%'";
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr><form action="testcases.php" method="get">';
								echo '<td><input type="hidden" name="testSuite_id" value='.$row['ID'].'>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Name'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '>'. $row['Status'] . '</td>';
								echo '<td style=background-color:'. $row['Result_Color'] . '>'. $row['Result'] . '</td>';
								echo '<td>'. $row['Elapsed_Time'] . '</td>';
								echo '<td>'. $row['TestCase_Count'] . '</td>';
								echo '<td>'. $row['Asserts'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '<td width=250>';
								echo '<button class="btn btn-info btn-sm"><clr-icon class="is-solid" shape="scroll" size="16"></clr-icon> View TestCases</button></form>';
								echo '</td>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
					</table>
		</div> <!-- End of Row -->
		
	</div><!-- End content-area -->
    <nav class="sidenav">
		<?php
			require_once 'components/Side_Bar.html';
		?>
	</nav>
</div><!-- End content-container (From Header) -->
</body>
</html>