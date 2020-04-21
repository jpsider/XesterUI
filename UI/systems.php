<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
if (!empty($_GET['TestName'])) {
	$TestName=$_GET['TestName'];
	$System_ID=$_GET['System_ID'];
	if(!empty($_GET['Remediate'])){
		$Remediate=$_GET['Remediate'];
		include 'components/database.php';
		$sql = "insert into testrun (Name,System_ID,STATUS_ID,RESULT_ID,Remediate,Hidden) VALUES ('$TestName','$System_ID','5','6','1',0)";
		$pdo = Database::connect();
		$pdo->query($sql);
		Database::disconnect();
		// Send the user back to the same page
		header("Refresh:0 url=index.php");		
	} else {
		include 'components/database.php';
		$sql = "insert into testrun (Name,System_ID,STATUS_ID,RESULT_ID,Hidden) VALUES ('$TestName','$System_ID','5','6',0)";
		$pdo = Database::connect();
		$pdo->query($sql);
		Database::disconnect();
		// Send the user back to the same page
		header("Refresh:0 url=index.php");
	}
} else {

?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->

    <h3>Systems</h3>
				<div class="row">
					<table id="example" class="table compact">
						<thead>
							<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Status</th>
							<th>date_modified</th>
							<th>View Targets</th>
							<th>Remediate</th>
							<th>TestRun Name</th>
							<th>Submit TestRun</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							if (!empty($_GET['System_ID'])) {
								$System_ID=$_GET['System_ID'];
								$sql = "select sys.ID, " 
										. "sys.SYSTEM_Name, "
										. "sys.Status_ID, "
										. "sys.date_modified, "																																			
										. "s.Status, "
										. "s.HtmlColor "
									. "from SYSTEMS sys "
									. "join STATUS s on sys.Status_ID=s.ID "
									. "where sys.ID like '$System_ID'";
							} else {
								$sql = "select sys.ID, " 
										. "sys.SYSTEM_Name, "
										. "sys.Status_ID, "
										. "sys.date_modified, "																																			
										. "s.Status, "
										. "s.HtmlColor "
									. "from SYSTEMS sys "
									. "join STATUS s on sys.Status_ID=s.ID ";
							}
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['SYSTEM_Name'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '><b>'. $row['Status'] . '</b></td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '<td><form action="targets.php" method="get"><input type="hidden" name="System_ID" value='.$row['ID'].'><input type="hidden" name="SYSTEM_Name" value='.$row['SYSTEM_Name'].'><button class="btn btn-info btn-sm"><clr-icon class="is-solid" shape="list" size="16"></clr-icon> View Targets</button></form></td>';
								echo '<td><form action="systems.php" method="get"><input type="checkbox" name="Remediate" value="1"></td>';
                                echo '<td><input type="text" name="TestName" value="Enter Test Name"></td>';
                                echo '<td><input type="hidden" name="System_ID" value='.$row['ID'].'><button class="btn btn-success-outline btn-sm"><clr-icon class="is-solid" shape="play" size="16"></clr-icon> Submit TestRun</button></form></td>';
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