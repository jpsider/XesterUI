<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
    <h3>StackTrace</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>TestCaseName</th>
							<th>Target_Name</th>
							<th>IP_Address</th>
							<th>Message</th>
							<th>StackTrace</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
                            include 'components/database.php';
                            if (!empty($_GET['testcase_id'])) {
                                $testcase_id=$_GET['testcase_id'];
                            } else {
                                $testcase_id="%%";
                            }

							$pdo = Database::connect();
							$sql = "select st.ID, " 
										. "tc.Name, "
										. "tc.Target_ID, "
										. "tc.Status_ID, "
										. "tc.Result_ID, "																																
										. "s.Status, "
										. "s.HtmlColor, "
										. "r.Name as Result, "
										. "r.HtmlColor as Result_Color, "
										. "t.Target_Name, "
										. "t.IP_Address, "
										. "t.System_ID, "
										. "st.Testcase_ID, "
										. "st.Message, "
										. "st.Stacktrace "
									. "from STACKTRACE st "
									. "join testcases tc on st.Testcase_ID=tc.ID "
									. "join STATUS s on tc.Status_ID=s.ID "
									. "join RESULTS r on tc.Result_ID=r.ID "
									. "join targets t on tc.Target_ID=t.ID "
									. "where st.testcase_id like '$testcase_id'";

							foreach ($pdo->query($sql) as $row) {
								echo '<tr><form action="systems.php" method="get">';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Name'] . '</td>';
								echo '<td>'. $row['Target_Name'] . '</td>';
								echo '<td>'. $row['IP_Address'] . '</td>';
								echo '<td>'. $row['Message'] . '</td>';
								echo '<td>'. $row['Stacktrace'] . '</td>';
								echo '<td><input type="hidden" name="System_ID" value='.$row['System_ID'].'><input type="submit" class="btn btn-success-outline btn-sm" value="Remediate"></form></td>';

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