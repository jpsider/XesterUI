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
				<h3>vDocumentation - Host FibreChannel Info</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Target_ID</th>
								<th>HostName</th>					
								<th>Device</th>
								<th>Model</th>
								<th>Node WWN</th>
								<th>Port WWN</th>
								<th>Driver</th>
								<th>Speed (GB)</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$Device = "null";
							$Model = "null";
							$Node_WWN = "null";
							$Port_WWN = "null";
							$Driver = "null";
							$Speed_GB = "null";
							$Status = "null";
							$pdo = Database::connect();
							$sql = "select Distinct inv.Target_ID, "
										. "t.Target_Name as Hostname "
										. "from Inventory_Attributes inv "
										. "join Targets t on inv.Target_ID=t.ID ";
	
							foreach ($pdo->query($sql) as $row) {
								$Target_ID = $row['Target_ID'];
								$Hostname = $row['Hostname'];
								$sql1 = "select inv.ID, "
										. "inv.Target_ID, "
										. "inv.Available_Attributes_ID, "
										. "inv.Attribute_Value, "
										. "aa.Name as Attribute_Name, "
										. "aa.Attribute_Type_ID, "
										. "att.Name as Attribute_Type "
									. "from INVENTORY_ATTRIBUTES inv "
									. "join AVAILABLE_ATTRIBUTES aa on inv.Available_Attributes_ID=aa.ID "
									. "join ATTRIBUTE_TYPE att on aa.Attribute_Type_ID=att.ID "
									. "where inv.Target_ID=$Target_ID and aa.Attribute_Type_ID=5";

									echo '<tr>';
									echo '<td>'. $Target_ID .'</td>';
									echo '<td>'. $Hostname .'</td>';						

									foreach ($pdo->query($sql1) as $row1) {	
										$Attribute_Name = $row1['Attribute_Name'];
										$Attribute_Value =  $row1['Attribute_Value'];			
										if($Attribute_Name == "Device") {
											$Device = $Attribute_Value;
										}
										if($Attribute_Name == "Model") {
											$Model = $Attribute_Value;
										}
										if($Attribute_Name == "Node WWN") {
											$Node_WWN = $Attribute_Value;
										}
										if($Attribute_Name == "Port WWN") {
											$Port_WWN = $Attribute_Value;
										}
										if($Attribute_Name == "Driver") {
											$Driver = $Attribute_Value;
										}
										if($Attribute_Name == "Speed (GB)") {
											$Speed_GB = $Attribute_Value;
										}
										if($Attribute_Name == "Status") {
											$Status = $Attribute_Value;
										}
									}

									echo '<td>'. $Device .'</td>';
									echo '<td>'. $Model .'</td>';
									echo '<td>'. $Node_WWN .'</td>';
									echo '<td>'. $Port_WWN .'</td>';
									echo '<td>'. $Driver .'</td>';
									echo '<td>'. $Speed_GB .'</td>';
									echo '<td>'. $Status .'</td>';
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