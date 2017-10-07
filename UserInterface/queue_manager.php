<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
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
<script> 
	$(document).ready(function() {
	$('#example').dataTable();
	});
</script>
<body>
    <div class="container" style="margin-left:10px">
    	<div class="row">
			<?php
				require_once 'components/Side_Bar.html';
			?>
			<div class="col-sm-9 col-md-10 col-lg-10 main">
				<h3>XesterUI Queue Manager</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
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
									. 'join x_status s on qm.Status_ID=s.ID ';
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Wait'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '>'. $row['Status'] . '</td>';
								echo '<td><form action="singleLogByName.php" method="get"><input type="hidden" name="Log_File" value='.$row['Qman_Log'].'><input type="submit" class="btn btn-info" value="View Log"></form></td>';
								echo '<td>'. $row['Heartbeat'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '<td width=250>';
								if ($row['Status_ID'] == 1) {
									echo '<a class="btn btn-success" href="queue_manager.php?new_status_id=3">Start Manager</a>';
								} elseif ($row['Status_ID'] == 2) {
									echo '<a class="btn btn-danger" href="queue_manager.php?new_status_id=4">Stop Manager</a>';
								} elseif ($row['Status_ID'] == 3) {
									echo '<a class="btn btn-info" href="queue_manager.php">Refresh</a>';
								}else {
									echo '<a class="btn btn-info" href="queue_manager.php">Refresh</a>';
								}
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
<?php
  }
?>
</html>