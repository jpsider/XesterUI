<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['id'])) {
		$new_status_id=$_GET['new_status_id'];
		$id=$_GET['id'];
		include 'components/database.php';
		// Update the database to set the test to aborted
		$sql = "UPDATE testrun_manager SET Status_ID = $new_status_id where ID like $id";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Send the user back to the stme page (without get)
		header("Refresh:0 url=testrun_manager.php");
	} else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->

    <h3>TestRun Managers</h3>
				<div class="row">
					<table id="example" class="table table-compact">
						<thead>
							<tr>
							<th>ID</th>
							<th>Status</th>
							<th>Wait</th>
							<th>Max_Concurrent</th>
							<th>LogFile</th>
							<th>Heartbeat</th>
							<th>date_modified</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select tm.ID, ' 
										. 'tm.Status_ID, '
										. 'tm.Max_Concurrent, '
										. 's.Status, '
										. 's.HtmlColor, '
										. 'tm.Wait, '
										. 'tm.Log_File, '
										. 'tm.Heartbeat, '
										. 'tm.date_modified '
									. 'from testrun_manager tm '
									. 'join STATUS s on tm.Status_ID=s.ID ';
						
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '><b>' . $row['Status'] . '</b></td>';								
								echo '<td>'. $row['Wait'] . '</td>';
								echo '<td>'. $row['Max_Concurrent'] . '</td>';
								echo '<td><form action="viewlog.php" method="get"><input type="hidden" name="Log_File" value='.$row['Log_File'].'><button class="btn btn-info btn-sm"><clr-icon class="is-solid" size="16" shape="list"></clr-icon> View Log</button></form></td>';
								echo '<td>'. $row['Heartbeat'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
                                if ($row['Status_ID'] == 1) {
                                    echo '<td><form action="testrun_manager.php" method="get"><input type="hidden" name="new_status_id" value="3"><input type="hidden" name="id" value="'. $row['ID'] . '"><button class="btn btn-success-outline btn-sm"><clr-icon class="is-solid" shape="key" size="16"></clr-icon> Start TestRun Manager</button></form></td>';
                                } elseif ($row['Status_ID'] == 2) {
                                    echo '<td><form action="testrun_manager.php" method="get"><input type="hidden" name="new_status_id" value="4"><input type="hidden" name="id" value="'. $row['ID'] . '"><button class="btn btn-danger-outline btn-sm"><clr-icon class="is-solid" shape="stop" size="16"></clr-icon> Stop TestRun Manager</button></form></td>';
                                } elseif ($row['Status_ID'] == 3) {
                                    echo '<td><form action="testrun_manager.php" method="get"><button class="btn btn-info btn-sm"><clr-icon class="is-solid" shape="refresh" size="16"></clr-icon> Refresh</button></form></td>';
                                } elseif ($row['Status_ID'] == 4) {
                                    echo '<td><form action="testrun_manager.php" method="get"><button class="btn btn-info btn-sm"><clr-icon class="is-solid" shape="refresh" size="16"></clr-icon> Refresh</button></form></td>';								
                                }else {
                                    echo '';
                                }
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
<?php
	}
?>
</html>