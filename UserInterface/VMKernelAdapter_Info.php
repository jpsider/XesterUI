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
				<h3>vDocumentation - Host VMKernel Adapter Info</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Target_ID</th>
								<th>HostName</th>					
                                <th>Name</th>
                                <th>MAC</th>
                                <th>MTU</th>
                                <th>IP</th>
                                <th>Subnet Mask</th>
                                <th>TCP/IP Stack</th>
                                <th>Default Gateway</th>
                                <th>DNS</th>
                                <th>PortGroup Name</th>
                                <th>VLAN ID</th>
                                <th>Enabled Services</th>
                                <th>vSwitch</th>
                                <th>vSwitch MTU</th>
                                <th>Active adapters</th>
                                <th>Standby adapters</th>
                                <th>Unused adapters</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
                            $Name = "null";
                            $MAC = "null";
                            $MTU = "null";
                            $IP = "null";
                            $Subnet_Mask = "null";
                            $TCPIP_Stack = "null";
                            $Default_Gateway = "null";
                            $DNS = "null";
                            $PortGroup_Name = "null";
                            $VLAN_ID = "null";
                            $Enabled_Services = "null";
                            $vSwitch = "null";
                            $vSwitch_MTU = "null";
                            $Active_adapters = "null";
                            $Standby_adapters = "null";
                            $Unused_adapters = "null";
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
									. "where inv.Target_ID=$Target_ID and aa.Attribute_Type_ID=9";

									echo '<tr>';
									echo '<td>'. $Target_ID .'</td>';
									echo '<td>'. $Hostname .'</td>';						

									foreach ($pdo->query($sql1) as $row1) {	
										$Attribute_Name = $row1['Attribute_Name'];
										$Attribute_Value =  $row1['Attribute_Value'];			
										if($Attribute_Name == "Name") {
											$Name = $Attribute_Value;
										}
										if($Attribute_Name == "MAC") {
											$MAC = $Attribute_Value;
										}
										if($Attribute_Name == "MTU") {
											$MTU = $Attribute_Value;
										}
										if($Attribute_Name == "IP") {
											$IP = $Attribute_Value;
										}
										if($Attribute_Name == "Subnet Mask") {
											$Subnet_Mask = $Attribute_Value;
										}
										if($Attribute_Name == "TCP/IP Stack") {
											$TCPIP_Stack = $Attribute_Value;
										}
										if($Attribute_Name == "Default Gateway") {
											$Default_Gateway = $Attribute_Value;
										}
										if($Attribute_Name == "DNS") {
											$DNS = $Attribute_Value;
										}
										if($Attribute_Name == "PortGroup Name") {
											$PortGroup_Name = $Attribute_Value;
										}
										if($Attribute_Name == "VLAN ID") {
											$VLAN_ID = $Attribute_Value;
										}
										if($Attribute_Name == "Enabled Services") {
											$Enabled_Services = $Attribute_Value;
										}
										if($Attribute_Name == "vSwitch") {
											$vSwitch = $Attribute_Value;
										}
										if($Attribute_Name == "vSwitch MTU") {
											$vSwitch_MTU = $Attribute_Value;
										}
										if($Attribute_Name == "Active adapters") {
											$Active_adapters = $Attribute_Value;
										}
										if($Attribute_Name == "Standby adapters") {
											$Standby_adapters = $Attribute_Value;
										}
										if($Attribute_Name == "Unused adapters") {
											$Unused_adapters = $Attribute_Value;
										}
									}

                                    echo '<td>'. $Name .'</td>';
                                    echo '<td>'. $MAC .'</td>';
                                    echo '<td>'. $MTU .'</td>';
                                    echo '<td>'. $IP .'</td>';
                                    echo '<td>'. $Subnet_Mask .'</td>';
                                    echo '<td>'. $TCPIP_Stack .'</td>';
                                    echo '<td>'. $Default_Gateway .'</td>';
                                    echo '<td>'. $DNS .'</td>';
                                    echo '<td>'. $PortGroup_Name .'</td>';
                                    echo '<td>'. $VLAN_ID .'</td>';
                                    echo '<td>'. $Enabled_Services .'</td>';
                                    echo '<td>'. $vSwitch .'</td>';
                                    echo '<td>'. $vSwitch_MTU .'</td>';
                                    echo '<td>'. $Active_adapters .'</td>';
                                    echo '<td>'. $Standby_adapters .'</td>';
                                    echo '<td>'. $Unused_adapters .'</td>';
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