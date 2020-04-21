<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
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
		//Send the user back to the same page (without get)
		header("Refresh:0 url=targets.php");
	} else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
    <h3>Add a Target</h3>
				<div class="row">
					<table id="example" class="table table-compact" style="width:450px">
							<tr><form action="addTarget.php" method="get">
							<th style="text-align:right">Target Name</th>
							<td style="text-align:left"><input type="text" name="TargetName" value="Enter New Target"></td>
							</tr>
							<tr>
                            <th style="text-align:right">Config.json Path</th>
							<td style="text-align:left"><input type="text" name="ConfigPath" value="Enter Config.json path"></td>		
							</tr>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = "select ID as Target_Type_ID,Name,date_modified from Target_Types";
                            
                            echo '<tr><th style="text-align:right"><label for="Target_Type_ID">Choose a Target Type:</th><td style="text-align:left"></label><select>';
							foreach ($pdo->query($sql) as $row) {
								echo '<option name="Target_Type_ID" value='.$row['Target_Type_ID'].'> '. $row['Name'] . '';
							}
                            Database::disconnect();
                            
                            echo '</select></td></tr>';
							?>
							<?php 
							$pdo = Database::connect();
							$sql = "select ID as System_ID,SYSTEM_Name,date_modified from SYSTEMS";

                            echo '<tr><th style="text-align:right"><label for="System_ID">Choose a System:</th><td style="text-align:left"></label><select>';
							foreach ($pdo->query($sql) as $row) {
								echo '<option name="System_ID" value='.$row['System_ID'].'> '. $row['SYSTEM_Name'] . '';
							}
                            Database::disconnect();
                            echo '</select></td></tr>';

							?>
							<?php 
							$pdo = Database::connect();
							$sql = "select ID as Password_ID,Username,date_modified from PASSWORDS";

                            echo '<tr><th style="text-align:right"><label for="System_ID">Choose a UserName:</th><td style="text-align:left"></label><select>';
							foreach ($pdo->query($sql) as $row) {
								echo '<option name="Password_ID" value='.$row['Password_ID'].'> '. $row['Username'] . '';
							}
                            Database::disconnect();
                            echo '</select></td></tr>';
							?>
						<tr><th style="text-align:right">Create New Target</th><td><input type="hidden" name="NewTarget" value="TRUE"><button class="btn btn-success-outline btn-sm"><clr-icon class="is-solid" shape="pencil" size="16"></clr-icon> Create Target</button></form></td></tr>
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