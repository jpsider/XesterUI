<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
    <h3>TestCase Results</h3>
				<div class="row">
					<table id="example" class="table table-compact">
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
                            <th>Action</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
                            include 'components/database.php';
                            if (!empty($_GET['testSuite_id'])) {
                                $testSuite_id=$_GET['testSuite_id'];
                            } else {
                                $testSuite_id="%%";
                            }

                            if (!empty($_GET['testRun_id'])) {
                                $testRun_id=$_GET['testRun_id'];
                            } else {
                                $testRun_id="%%";
                            }

                            if (!empty($_GET['Target_Id'])) {
                                $Target_Id=$_GET['Target_Id'];
                            } else {
                                $Target_Id="%%";
                            }

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
                                        . "t.IP_Address, "
                                        . "ts.Name as TestSuiteName "
									. "from TESTCASES tc "
									. "join STATUS s on tc.Status_ID=s.ID "
									. "join RESULTS r on tc.Result_ID=r.ID "
                                    . "join targets t on tc.Target_ID=t.ID "
                                    . "join testsuites ts on tc.TEST_SUITE_ID=ts.ID "
									. "where tc.TEST_SUITE_ID like '%$testSuite_id%' and ts.TestRun_ID like '%$testRun_id%' and tc.Target_ID like '%$Target_Id%'";

							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Name'] . '</td>';
								echo '<td>'. $row['Target_Name'] . '</td>';
								echo '<td>'. $row['IP_Address'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '><b>'. $row['Status'] . '</b></td>';
								if ($row['Result_ID'] == 1) {
									echo '<td style=background-color:'. $row['Result_Color'] . '><b>'. $row['Result'] . '</b></td>';
								} elseif ($row['Result_ID'] == 2) {
									echo '<td style=background-color:'. $row['Result_Color'] . '><form action="view_stacktrace.php" method="get"><input type="hidden" name="testcase_id" value='.$row['ID'].'><button class="btn btn-white-outline btn-sm"><clr-icon class="is-solid" shape="eye" size="16"></clr-icon> View StackTrace</button></form></td>';
								} else {
									echo '<td style=background-color:'. $row['Result_Color'] . '>'. $row['Result'] . '</td>';
								}
								echo '<td>'. $row['Elapsed_Time'] . '</td>';
								echo '<td>'. $row['Asserts'] . '</td>';
								echo '<td width=250>';
								echo '<form action="SubmitTestRun.php" method="get"><input type="hidden" name="testSuite_id" value='.$row['ID'].'><button class="btn btn-yellow-outline btn-sm"><clr-icon class="is-solid" shape="wand" size="16"></clr-icon> Remediate</button></form>';
                                echo '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
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