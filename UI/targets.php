<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->

    <h3>Targets</h3>
				<div class="row">
					<table id="example" class="table table-compact">
						<thead>
							<tr>
							<th>ID</th>
							<th>Target Name</th>
							<th>IP_Address</th>
							<th>Target_Type</th>							
							<th>System_Name</th>
							<th>Status</th>
							<th>Action</th>
							<th>View TestCases</th>
                            <th>Config Path</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
                            <?php
                            if (!empty($_GET['System_ID'])) {
                                $System_ID=$_GET['System_ID'];
                            } else {
                                $System_ID="%%";
                            }

							include 'components/database.php';
							$pdo = Database::connect();
							$sql = "select t.ID, " 
										. "t.Target_Name, "
										. "t.Target_Type_ID, "
										. "t.IP_Address, "
										. "t.Status_ID, "
										. "t.date_modified, "													
										. "t.System_ID, "																							
										. "t.Config_File, "																							
										. "s.Status, "
										. "s.HtmlColor, "
										. "sys.SYSTEM_Name, "
										. "tt.Name as Target_Type_Name "
									. "from TARGETS t "
									. "join STATUS s on t.Status_ID=s.ID "
									. "join SYSTEMS sys on t.System_ID=sys.ID "
                                    . "join Target_Types tt on t.Target_Type_ID=tt.ID "
                                    . "where t.System_ID like '%$System_ID%'";
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr><form action="systems.php" method="get">';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Target_Name'] . '</td>';
								echo '<td>'. $row['IP_Address'] . '</td>';
								echo '<td>'. $row['Target_Type_Name'] . '</td>';
								echo '<td><input type="hidden" name="system_id" value='.$row['System_ID'].'>'. $row['SYSTEM_Name'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '><b>'. $row['Status'] . '</b></td>';
								echo '<td width=250>';
								echo '<button class="btn btn-success-outline btn-sm"><clr-icon class="is-solid" shape="play" size="16"></clr-icon> Submit TestRun</button></form>';
								echo '</td>';
								echo '<td><form action="testcases.php" method="get"><button class="btn btn-info btn-sm"><clr-icon class="is-solid" shape="history" size="16"></clr-icon> History</button><input type="hidden" name="Target_Id" value='.$row['ID'].'></form></td>';
                                echo '<td>'. $row['Config_File'] . '</td>';
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