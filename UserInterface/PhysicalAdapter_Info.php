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
				<h3>vDocumentation - Host Physical Adapter Info</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Target_ID</th>
								<th>HostName</th>					
								<th>Name</th>
								<th>Slot Description</th>
								<th>Device</th>
								<th>Duplex</th>
								<th>Link</th>
								<th>MAC</th>
								<th>MTU</th>
								<th>Speed</th>
								<th>vSwitch</th>
								<th>vSwitch MTU</th>
								<th>Discovery Protocol</th>
								<th>Device ID</th>
								<th>Device IP</th>
								<th>Port</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$Name = "null";
							$Slot_Description = "null";
							$Device = "null";
							$Duplex = "null";
							$Link = "null";
							$MAC = "null";
							$MTU = "null";
							$Speed = "null";
							$vSwitch = "null";
							$vSwitch_MTU = "null";
							$Discovery_Protocol = "null";
							$Device_ID = "null";
							$Device_IP = "null";
							$Port = "null";
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
									. "where inv.Target_ID=$Target_ID and aa.Attribute_Type_ID=8";

									echo '<tr>';
									echo '<td>'. $Target_ID .'</td>';
									echo '<td>'. $Hostname .'</td>';						

									foreach ($pdo->query($sql1) as $row1) {	
										$Attribute_Name = $row1['Attribute_Name'];
										$Attribute_Value =  $row1['Attribute_Value'];			
										if($Attribute_Name == "Name") {
											$Name = $Attribute_Value;
										}
										if($Attribute_Name == "Slot Description") {
											$Slot_Description = $Attribute_Value;
										}
										if($Attribute_Name == "Device") {
											$Device = $Attribute_Value;
										}
										if($Attribute_Name == "Duplex") {
											$Duplex = $Attribute_Value;
										}
										if($Attribute_Name == "Link") {
											$Link = $Attribute_Value;
										}
										if($Attribute_Name == "MAC") {
											$MAC = $Attribute_Value;
										}
										if($Attribute_Name == "MTU") {
											$MTU = $Attribute_Value;
										}
										if($Attribute_Name == "Speed") {
											$Speed = $Attribute_Value;
										}
										if($Attribute_Name == "vSwitch") {
											$vSwitch = $Attribute_Value;
										}
										if($Attribute_Name == "vSwitch MTU") {
											$vSwitch_MTU = $Attribute_Value;
										}
										if($Attribute_Name == "Discovery Protocol") {
											$Discovery_Protocol = $Attribute_Value;
										}
										if($Attribute_Name == "Device ID") {
											$Device_ID = $Attribute_Value;
										}
										if($Attribute_Name == "Device IP") {
											$Device_IP = $Attribute_Value;
										}
										if($Attribute_Name == "Port") {
											$Port = $Attribute_Value;
										}
									}

									echo '<td>'. $Name .'</td>';
									echo '<td>'. $Slot_Description .'</td>';
									echo '<td>'. $Device .'</td>';
									echo '<td>'. $Duplex .'</td>';
									echo '<td>'. $Link .'</td>';
									echo '<td>'. $MAC .'</td>';
									echo '<td>'. $MTU .'</td>';
									echo '<td>'. $Speed .'</td>';
									echo '<td>'. $vSwitch .'</td>';
									echo '<td>'. $vSwitch_MTU .'</td>';
									echo '<td>'. $Discovery_Protocol .'</td>';
									echo '<td>'. $Device_ID .'</td>';
									echo '<td>'. $Device_IP .'</td>';
									echo '<td>'. $Port .'</td>';
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