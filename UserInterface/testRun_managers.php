<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
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
		header("Refresh:0 url=TestRun_managers.php");
	} else {
?>
<script> 
	$(document).ready(function() {
		$('#extmple').dataTable();
	});
</script>
<body>
    <div class="container" style="margin-left:10px">
    	<div class="row">
			<?php
				require_once 'components/Side_Bar.html';
			?>
			<div class="col-sm-9 col-md-10 col-lg-10 main">
				<h3>XesterUI TestRun Managers</h3>
				<div class="row">
					<table id="extmple" class="table table-striped table-bordered">
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
									. 'join X_status s on tm.Status_ID=s.ID ';
						
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '>' . $row['Status'] . '</td>';								
								echo '<td>'. $row['Wait'] . '</td>';
								echo '<td>'. $row['Max_Concurrent'] . '</td>';
								echo '<td><form action="singleLogByName.php" method="get"><input type="hidden" name="Log_File" value='.$row['Log_File'].'><input type="submit" class="btn btn-info" value="View Log"></form></td>';
								echo '<td>'. $row['Heartbeat'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
							   	echo '<td>';							   	
								if ($row['Status_ID'] == 1) {
									echo '<a class="btn btn-success" href="testRun_managers.php?id='.$row['ID'].'&new_status_id=3">Start TestRun MGR</a>';
							   	} elseif ($row['Status_ID'] == 2) {
									echo '<a class="btn btn-danger" href="testRun_managers.php?id='.$row['ID'].'&new_status_id=4">Stop TestRun MGR</a>';
							   	} elseif ($row['Status_ID'] == 3) {
									echo '<a class="btn btn-info" href="testRun_managers.php">Refresh</a>';
							   	} elseif ($row['Status_ID'] == 4) {
									echo '<a class="btn btn-info" href="testRun_managers.php">Refresh</a>';									
								}else {
									echo '';
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