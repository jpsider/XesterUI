<!DOCTYPE html>
<html lang="en">
<?php
$System_ID=$_GET['System_ID'];
$SYSTEM_Name=$_GET['SYSTEM_Name'];

?>
<?php
	require_once 'components/header.php';
?>
<script> 
	$(document).ready(function() {
		$('#example').dataTable( {
			"order":[[ 0, "asc" ]]
		});
	});
</script>
<body>
    <div class="container" style="margin-left:10px">
    	<div class="row">
			<?php
				require_once 'components/Side_Bar.html';
			?>
			<div class="col-sm-9 col-md-10 col-lg-10 main">
				<?php
				echo "<h3>XesterUI Targets for $SYSTEM_Name </h3>"
				?>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Target_Type_ID</th>
							<th>IP_Address</th>
							<th>System_Name</th>
							<th>Status</th>
							<th>date_modified</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = "select t.ID, " 
										. "t.Target_Name, "
										. "t.Target_Type_ID, "
										. "t.IP_Address, "
										. "t.Status_ID, "
										. "t.date_modified, "													
										. "t.System_ID, "																							
										. "s.Status, "
										. "s.HtmlColor, "
										. "sys.SYSTEM_Name, "
										. "tt.Name as Target_Type_Name "
									. "from TARGETS t "
									. "join x_status s on t.Status_ID=s.ID "
									. "join SYSTEMS sys on t.System_ID=sys.ID "
									. "join Target_Types tt on t.Target_Type_ID=tt.ID "
									. "where t.System_ID like $System_ID";
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr><form action="systems.php" method="get">';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Target_Name'] . '</td>';
								echo '<td>'. $row['IP_Address'] . '</td>';
								echo '<td>'. $row['Target_Type_Name'] . '</td>';
								echo '<td><input type="hidden" name="system_id" value='.$row['System_ID'].'>'. $row['SYSTEM_Name'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '>'. $row['Status'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '<td width=250>';
								echo '<input type="submit" class="btn btn-success" value="Submit TestRun"></form>';
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

</html>