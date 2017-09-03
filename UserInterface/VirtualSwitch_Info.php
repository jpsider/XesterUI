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
				<h3>vDocumentation - Host VirtualSwitch Info</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Target_ID</th>
								<th>HostName</th>					
								<th>Type</th>
								<th>Version</th>
								<th>Name</th>
								<th>Uplink/ConnectedAdapters</th>
								<th>PortGroup</th>
								<th>VLAN ID</th>
								<th>Active adapters</th>
								<th>Standby adapters</th>
								<th>Unused adapters</th>
								<th>Security Promiscuous/MacChanges/ForgedTransmits</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$Type = "null";
							$Version = "null";
							$Name = "null";
							$Uplink_ConnectedAdapters = "null";
							$PortGroup = "null";
							$VLAN_ID = "null";
							$Active_adapters = "null";
							$Standby_adapters = "null";
							$Unused_adapters = "null";
							$Security_Promiscuous_MacChanges_ForgedTransmits = "null";
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
									. "where inv.Target_ID=$Target_ID and aa.Attribute_Type_ID=3";

									echo '<tr>';
									echo '<td>'. $Target_ID .'</td>';
									echo '<td>'. $Hostname .'</td>';						

									foreach ($pdo->query($sql1) as $row1) {	
										$Attribute_Name = $row1['Attribute_Name'];
										$Attribute_Value =  $row1['Attribute_Value'];			
										if($Attribute_Name == "Type") {
											$Type = $Attribute_Value;
										}
										if($Attribute_Name == "Version") {
											$Version = $Attribute_Value;
										}
										if($Attribute_Name == "Name") {
											$Name = $Attribute_Value;
										}
										if($Attribute_Name == "Uplink/ConnectedAdapters") {
											$Uplink_ConnectedAdapters = $Attribute_Value;
										}
										if($Attribute_Name == "PortGroup") {
											$PortGroup = $Attribute_Value;
										}
										if($Attribute_Name == "VLAN ID") {
											$VLAN_ID = $Attribute_Value;
										}
										if($Attribute_Name == "Active adapters") {
											$Activeadapters = $Attribute_Value;
										}
										if($Attribute_Name == "Standby adapters	") {
											$Standby_adapters = $Attribute_Value;
										}
										if($Attribute_Name == "Unused adapters	") {
											$Unused_adapters = $Attribute_Value;
										}
										if($Attribute_Name == "Security Promiscuous/MacChanges/ForgedTransmits") {
											$Security_Promiscuous_MacChanges_ForgedTransmits = $Attribute_Value;
										}
									}

									echo '<td>'. $Type .'</td>';
									echo '<td>'. $Version .'</td>';
									echo '<td>'. $Name .'</td>';
									echo '<td>'. $Uplink_ConnectedAdapters .'</td>';
									echo '<td>'. $PortGroup .'</td>';
									echo '<td>'. $VLAN_ID .'</td>';
									echo '<td>'. $Active_adapters .'</td>';
									echo '<td>'. $Standby_adapters .'</td>';
									echo '<td>'. $Unused_adapters .'</td>';
									echo '<td>'. $Security_Promiscuous_MacChanges_ForgedTransmits .'</td>';
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