<!DOCTYPE html>
<html lang="en">
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
				<h3>vDocumentation - Hardware Info</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>ID</th>
								<th>Target_ID</th>
								<th>HostName</th>					
								<th>Attribute_Name</th>
								<th>Attribute_Type</th>
								<th>Attribute_Value</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = "select inv.ID, "
										. "inv.Target_ID, "
										. "inv.Available_Attributes_ID, "
										. "inv.Attribute_Value, "
										. "aa.Name as Attribute_Name, "
										. "aa.Attribute_Type_ID, "
										. "att.Name as Attribute_Type, "
										. "t.Target_Name as Hostname "
									. "from INVENTORY_ATTRIBUTES inv "
									. "join Targets t on inv.Target_ID=t.ID "
									. "join AVAILABLE_ATTRIBUTES aa on inv.Available_Attributes_ID=aa.ID "
									. "join ATTRIBUTE_TYPE att on aa.Attribute_Type_ID=att.ID ";
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Target_ID'] . '</td>';
								echo '<td>'. $row['Hostname'] . '</td>';					
								echo '<td>'. $row['Attribute_Name'] . '</td>';
								echo '<td>'. $row['Attribute_Type'] . '</td>';
								echo '<td>'. $row['Attribute_Value'] . '</td>';
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