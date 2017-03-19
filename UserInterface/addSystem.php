<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewSystem'])) {
		$SystemName=$_GET['SystemName'];
		$ConfigPath=$_GET['ConfigPath'];
		include 'components/database.php';
		// Update the database to set the test to aborted
		$sql = "insert into systems (System_Name,Config_File,Status_ID) VALUES ('$SystemName','$ConfigPath','11')";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Send the user back to the stme page (without get)
		header("Refresh:0 url=systems.php");
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
							<th>System Name</th>
							<th>Config.json File</th>
							<th>Add System</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							<form>
							<td><input type="text" name="SystemName" value="Enter New System"></td>
							<td><input type="text" name="ConfigPath" value="System Config File"></td>
							<td><input type="hidden" name="NewSystem" value="TRUE"><input type="submit" class="btn btn-success" value="Create System"></td>
							</form>
							</tr>
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