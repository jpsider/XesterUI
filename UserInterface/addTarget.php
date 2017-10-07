<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewTarget'])) {
		$TargetName=$_GET['TargetName'];
		$Target_Type_ID=$_GET['Target_Type_ID'];
		$Password_ID=$_GET['Password_ID'];
		$System_ID=$_GET['System_ID'];
		$ConfigPath=$_GET['ConfigPath'];
		include 'components/database.php';
		// Update the database to set the test to aborted
		$sql = "insert into targets (Target_Name,Target_Type_ID,Status_ID,Password_ID,System_ID,Config_File) VALUES ('$TargetName','$Target_Type_ID','11','$Password_ID','$System_ID','$ConfigPath')";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Send the user back to the stme page (without get)
		header("Refresh:0 url=targets.php");
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
				<h3>XesterUI - Add a Target</h3>
				<div class="row">
					<table id="extmple" class="table table-striped table-bordered">
							<tr>
							<th>System Name</th>
							<th>Config.json Path</th>
							<th></th>
							</tr>
							<tr>
							<form action="addTarget.php" method="get">
							<td><input type="text" name="TargetName" value="Enter New Target"></td>
							<td><input type="text" name="ConfigPath" value="Enter Config.json path"></td>		
							<td></td>
							</tr>
							<tr>
							<th>Target Type ID</th>
							<th>Name</th>
							<th>date_modified</th>
							</tr>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = "select ID as Target_Type_ID,Name,date_modified from Target_Types";
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['Target_Type_ID'] . '</td>';
								echo '<td><input type="checkbox" name="Target_Type_ID" value='.$row['Target_Type_ID'].'> '. $row['Name'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
							<tr>
							<th>System ID</th>
							<th>SYSTEM_Name</th>
							<th>date_modified</th>
							</tr>
							<?php 
							$pdo = Database::connect();
							$sql = "select ID as System_ID,SYSTEM_Name,date_modified from SYSTEMS";
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['System_ID'] . '</td>';
								echo '<td><input type="checkbox" name="System_ID" value='.$row['System_ID'].'> '. $row['SYSTEM_Name'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
							<tr>
							<th>User Name ID</th>
							<th>Username</th>
							<th>date_modified</th>
							</tr>
							<?php 
							$pdo = Database::connect();
							$sql = "select ID as Password_ID,Username,date_modified from PASSWORDS";
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['Password_ID'] . '</td>';
								echo '<td><input type="checkbox" name="Password_ID" value='.$row['Password_ID'].'> '. $row['Username'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						<tr><th></th><th>Create New Target</th><th></th></tr>
						<tr></td><td><td><input type="hidden" name="NewTarget" value="TRUE"><input type="submit" class="btn btn-success" value="Create Target"></td>
						</td></form><td></tr>
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