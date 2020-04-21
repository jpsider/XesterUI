<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewSystem'])) {
		$SystemName=$_GET['SystemName'];
		include 'components/database.php';
		// Update the database to set the test to aborted
		$sql = "insert into systems (System_Name,Status_ID) VALUES ('$SystemName','$ConfigPath','11')";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Send the user back to the stme page (without get)
		header("Refresh:0 url=systems.php");
	} else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->

    <h3>Add a System</h3>
		<div class="row">
			<table class="table table-compact" style="width:450px">
				<thead>
					<tr>
					<th>System Name</th>
					<th>Add System</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					<form>
					<td><input type="text" name="SystemName" value="Enter New System"></td>
					<td><input type="hidden" name="NewSystem" value="TRUE"><button class="btn btn-success-outline btn-sm"><clr-icon class="is-solid" shape="play" size="16"></clr-icon> Create System</button></td>
					</form>
					</tr>
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