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
				<h3>vDocumentation - Host ISCSI Info</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Target_ID</th>
								<th>HostName</th>					
								<th>Device</th>
								<th>ISCSI Name</th>
								<th>Model</th>
								<th>Send Targets</th>
								<th>Static Targets</th>
								<th>Port Group</th>
								<th>VMKernel Adapter</th>
								<th>Port Binding</th>
								<th>Path Status</th>
								<th>Physical Network Adapter</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$Device = "null";
							$ISCSI_Name = "null";
							$Model = "null";
							$Send_Targets = "null";
							$Static_Targets = "null";
							$Port_Group = "null";
							$VMKernel_Adapter = "null";
							$Port_Binding = "null";
							$Path_Status = "null";
							$Physical_Network_Adapter = "null";
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
									. "where inv.Target_ID=$Target_ID and aa.Attribute_Type_ID=4";

									echo '<tr>';
									echo '<td>'. $Target_ID .'</td>';
									echo '<td>'. $Hostname .'</td>';						

									foreach ($pdo->query($sql1) as $row1) {	
										$Attribute_Name = $row1['Attribute_Name'];
										$Attribute_Value =  $row1['Attribute_Value'];			
										if($Attribute_Name == "Device") {
											$Device = $Attribute_Value;
										}
										if($Attribute_Name == "ISCSI Name") {
											$ISCSI_Name = $Attribute_Value;
										}
										if($Attribute_Name == "Model") {
											$Model = $Attribute_Value;
										}
										if($Attribute_Name == "Send Targets") {
											$Send_Targets = $Attribute_Value;
										}
										if($Attribute_Name == "Static Targets") {
											$Static_Targets = $Attribute_Value;
										}
										if($Attribute_Name == "Port Group") {
											$Port_Group = $Attribute_Value;
										}
										if($Attribute_Name == "VMKernel Adapter") {
											$VMKernel_Adapter = $Attribute_Value;
										}
										if($Attribute_Name == "Port Binding") {
											$Port_Binding = $Attribute_Value;
										}
										if($Attribute_Name == "Path Status") {
											$Path_Status = $Attribute_Value;
										}
										if($Attribute_Name == "Physical Network Adapter") {
											$Physical_Network_Adapter = $Attribute_Value;
										}
									}
									echo '<td>'. $Device .'</td>';
									echo '<td>'. $ISCSI_Name .'</td>';
									echo '<td>'. $Model .'</td>';
									echo '<td>'. $Send_Targets .'</td>';
									echo '<td>'. $Static_Targets .'</td>';
									echo '<td>'. $Port_Group .'</td>';
									echo '<td>'. $VMKernel_Adapter .'</td>';
									echo '<td>'. $Port_Binding .'</td>';
									echo '<td>'. $Path_Status .'</td>';
									echo '<td>'. $Physical_Network_Adapter .'</td>';
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