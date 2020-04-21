<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['new_status_id'])) {
		$new_status_id=$_GET['new_status_id'];
		include 'components/database.php';
		// Update the database to set the test to aborted
		$sql = "UPDATE queue_manager SET Status_ID = $new_status_id";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Send the user back to the same page (without get)
		header("Refresh:0 url=queue_manager.php");
	} else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->

<h3>Queue Manager</h3></br>
				<div class="row">
					<table id="example" class="table table-compact">
						<thead>
							<tr>
							<th>ID</th>
							<th>Wait</th>
							<th>Status</th>
							<th>Log_File</th>
							<th>HeartBeat</th>
							<th>date_modified</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select qm.ID, ' 
										. 'qm.Status_ID, '
										. 'qm.Wait, '
										. 'qm.Log_File as Qman_Log, '
										. 'qm.Heartbeat, '
										. 'qm.date_modified, '
										. 's.HtmlColor, '
										. 's.Status '
									. 'from QUEUE_MANAGER qm '
									. 'join STATUS s on qm.Status_ID=s.ID ';
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Wait'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '><b>'. $row['Status'] . '</b></td>';
								echo '<td><form action="viewlog.php" method="get"><input type="hidden" name="Log_File" value='.$row['Qman_Log'].'><button class="btn btn-info btn-sm"><clr-icon class="is-solid" size="16" shape="list"></clr-icon> View Log</button></form></td>';
								echo '<td>'. $row['Heartbeat'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								if ($row['Status_ID'] == 1) {
									echo '<td><form action="queue_manager.php" method="get"><input type="hidden" name="new_status_id" value="3"><button class="btn btn-success-outline btn-sm"><clr-icon class="is-solid" shape="key" size="16"></clr-icon> Start Manager</button></form></td>';
								} elseif ($row['Status_ID'] == 2) {
									echo '<td><form action="queue_manager.php" method="get"><input type="hidden" name="new_status_id" value="4"><button class="btn btn-danger-outline btn-sm"><clr-icon class="is-solid" shape="stop" size="16"></clr-icon><b> Stop Manager</b></button></form></td>';
								} elseif ($row['Status_ID'] == 3) {
									echo '<td><form action="queue_manager.php" method="get"><button class="btn btn-info btn-sm"><clr-icon class="is-solid" shape="refresh" size="16"></clr-icon> Refresh</button></form></td>';
								}else {
									echo '<td><form action="queue_manager.php" method="get"><button class="btn btn-info btn-sm"><clr-icon class="is-solid" shape="refresh" size="16"></clr-icon> Refresh</button></form></td>';
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