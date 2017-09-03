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
				<h3>vDocumentation - Host Configuration Info</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Target_ID</th>
								<th>HostName</th>					
								<th>Make</th>
								<th>Model</th>
								<th>CPU Model</th>
								<th>Hyper-Threading</th>
								<th>Max EVC Mode</th>
								<th>Product</th>
								<th>Version</th>
								<th>Build</th>
								<th>Update</th>
								<th>Patch</th>
								<th>License Version</th>
								<th>License Key</th>
								<th>Connection State</th>
								<th>Standalone</th>
								<th>Cluster</th>
								<th>Virtual Datacenter</th>
								<th>vCenter</th>
								<th>Software/Patch Last Installed</th>
								<th>Software/Patch Name(s)</th>
								<th>Service</th>
								<th>Service Running</th>
								<th>Startup Policy</th>
								<th>NTP Client Enabled</th>
								<th>NTP Server</th>
								<th>Syslog Server</th>
								<th>Syslog Client Enabled</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$Make = "null";
							$Model = "null";
							$CPU_Model = "null";
							$Hyper_Threading = "null";
							$Max_EVC_Mode = "null";
							$Product = "null";
							$Version = "null";
							$Build = "null";
							$Update = "null";
							$Patch = "null";
							$License_Version = "null";
							$License_Key = "null";
							$Connection_State = "null";
							$Standalone = "null";
							$Cluster = "null";
							$Virtual_Datacenter = "null";
							$vCenter = "null";
							$Software_Patch_Last_Installed = "null";
							$Software_Patch_Names = "null";
							$Service = "null";
							$Service_Running = "null";
							$Startup_Policy = "null";
							$NTP_Client_Enabled = "null";
							$NTP_Server = "null";
							$Syslog_Server = "null";
							$Syslog_Client_Enabled = "null";
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
									. "where inv.Target_ID=$Target_ID and aa.Attribute_Type_ID=2";

									echo '<tr>';
									echo '<td>'. $Target_ID .'</td>';
									echo '<td>'. $Hostname .'</td>';						

									foreach ($pdo->query($sql1) as $row1) {	
										$Attribute_Name = $row1['Attribute_Name'];
										$Attribute_Value =  $row1['Attribute_Value'];			
										if($Attribute_Name == "Make") {
											$Make = $Attribute_Value;
										}
										if($Attribute_Name == "Model") {
											$Model = $Attribute_Value;
										}
										if($Attribute_Name == "CPU Model") {
											$CPU_Model = $Attribute_Value;
										}
										if($Attribute_Name == "Hyper-Threading") {
											$Hyper_Threading = $Attribute_Value;
										}
										if($Attribute_Name == "Max EVC Mode") {
											$Mac_EVC_Mode = $Attribute_Value;
										}
										if($Attribute_Name == "Product") {
											$Product = $Attribute_Value;
										}
										if($Attribute_Name == "Version") {
											$Version = $Attribute_Value;
										}
										if($Attribute_Name == "Build") {
											$Build = $Attribute_Value;
										}
										if($Attribute_Name == "Update") {
											$Update = $Attribute_Value;
										}
										if($Attribute_Name == "Patch") {
											$Patch = $Attribute_Value;
										}
										if($Attribute_Name == "License Version") {
											$License_Version = $Attribute_Value;
										}
										if($Attribute_Name == "License Key") {
											$License_Key = $Attribute_Value;
										}
										if($Attribute_Name == "Connection State") {
											$Connection_State = $Attribute_Value;
										}
										if($Attribute_Name == "Standalone") {
											$Standalone = $Attribute_Value;
										}
										if($Attribute_Name == "Cluster") {
											$Cluster = $Attribute_Value;
										}
										if($Attribute_Name == "Virtual Datacenter") {
											$Virtual_Datacenter = $Attribute_Value;
										}
										if($Attribute_Name == "vCenter") {
											$vCenter = $Attribute_Value;
										}
										if($Attribute_Name == "Software/Patch Last Installed") {
											$Software_Patch_Last_Installed = $Attribute_Value;
										}
										if($Attribute_Name == "Software/Patch Name(s)") {
											$Software_Patch_Names = $Attribute_Value;
										}
										if($Attribute_Name == "Service") {
											$Service = $Attribute_Value;
										}
										if($Attribute_Name == "Service Running") {
											$Service_Running = $Attribute_Value;
										}
										if($Attribute_Name == "Startup Policy") {
											$Startup_Policy = $Attribute_Value;
										}
										if($Attribute_Name == "NTP Client Enabled") {
											$NTP_Client_Enabled = $Attribute_Value;
										}
										if($Attribute_Name == "NTP Server") {
											$NTE_Server = $Attribute_Value;
										}
										if($Attribute_Name == "Syslog Server") {
											$Syslog_Server = $Attribute_Value;
										}
										if($Attribute_Name == "Syslog Client Enabled") {
											$Syslog_Client_Enabled = $Attribute_Value;
										}
										
									}

									echo '<td>'. $Make .'</td>';
									echo '<td>'. $Model .'</td>';
									echo '<td>'. $CPU_Model .'</td>';
									echo '<td>'. $Hyper_Threading .'</td>';
									echo '<td>'. $Max_EVC_Mode .'</td>';
									echo '<td>'. $Product .'</td>';
									echo '<td>'. $Version .'</td>';
									echo '<td>'. $Build .'</td>';
									echo '<td>'. $Update .'</td>';
									echo '<td>'. $Patch .'</td>';
									echo '<td>'. $License_Version .'</td>';
									echo '<td>'. $License_Key .'</td>';
									echo '<td>'. $Connection_State .'</td>';
									echo '<td>'. $Standalone .'</td>';
									echo '<td>'. $Cluster .'</td>';
									echo '<td>'. $Virtual_Datacenter .'</td>';
									echo '<td>'. $vCenter .'</td>';
									echo '<td>'. $Software_Patch_Last_Installed .'</td>';
									echo '<td>'. $Software_Patch_Names .'</td>';
									echo '<td>'. $Service .'</td>';
									echo '<td>'. $Service_Running .'</td>';
									echo '<td>'. $Startup_Policy .'</td>';
									echo '<td>'. $NTP_Client_Enabled .'</td>';
									echo '<td>'. $NTP_Server .'</td>';
									echo '<td>'. $Syslog_Server .'</td>';
									echo '<td>'. $Syslog_Client_Enabled .'</td>';
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