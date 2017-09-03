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
				<h3>vDocumentation - Host Hardware Info</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Target_ID</th>
								<th>HostName</th>					
								<th>Management IP</th>
								<th>RAC IP</th>
								<th>RAC Firmware</th>
								<th>Product</th>
								<th>Version</th>
								<th>Build</th>
								<th>Update</th>
								<th>Patch</th>
								<th>Make</th>
								<th>Model</th>
								<th>S/N</th>
								<th>BIOS</th>
								<th>BIOS Release Date</th>
								<th>CPU Model</th>
								<th>CPU Count</th>
								<th>CPU Core Total</th>
								<th>Speed (MHz)</th>
								<th>Memory (GB)</th>
								<th>Memory Slots Count</th>
								<th>Memory Slots Used</th>
								<th>Power Supplies</th>
								<th>Nic Count</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$Management_IP = "null";
							$RAC_IP = "null";
							$RAC_Firmware = "null";
							$Product = "null";
							$Version = "null";
							$Build = "null";
							$Update = "null";
							$Patch = "null";
							$Make = "null";
							$Model = "null";
							$SN = "null";
							$BIOS = "null";
							$BIOS_Release_Date = "null";
							$CPU_Model = "null";
							$CPU_Count = "null";
							$CPU_Core_Total = "null";
							$Speed_MHz = "null";
							$Memory_GB = "null";
							$Memory_Slots_Count = "null";
							$Memory_Slots_Used = "null";
							$Power_Supplies = "null";
							$Nic_Count = "null";
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
									. "where inv.Target_ID=$Target_ID and aa.Attribute_Type_ID=1";

									echo '<tr>';
									echo '<td>'. $Target_ID .'</td>';
									echo '<td>'. $Hostname .'</td>';						

									foreach ($pdo->query($sql1) as $row1) {	
										$Attribute_Name = $row1['Attribute_Name'];
										$Attribute_Value =  $row1['Attribute_Value'];			
										if($Attribute_Name == "Management IP") {
											$Management_IP = $Attribute_Value;
										}
										if($Attribute_Name == "RAC IP") {
											$RAC_IP = $Attribute_Value;
										}
										if($Attribute_Name == "RAC Firmware") {
											$RAC_Firmware = $Attribute_Value;
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
										if($Attribute_Name == "Make") {
											$Make = $Attribute_Value;
										}
										if($Attribute_Name == "Model") {
											$Model = $Attribute_Value;
										}
										if($Attribute_Name == "S/N") {
											$SN = $Attribute_Value;
										}
										if($Attribute_Name == "BIOS") {
											$BIOS = $Attribute_Value;
										}
										if($Attribute_Name == "BIOS Release Date") {
											$BIOS_Release_Date = $Attribute_Value;
										}
										if($Attribute_Name == "CPU Model") {
											$CPU_Model = $Attribute_Value;
										}
										if($Attribute_Name == "CPU Count") {
											$CPU_Count = $Attribute_Value;
										}
										if($Attribute_Name == "CPU Core Total") {
											$CPU_Core_Total = $Attribute_Value;
										}
										if($Attribute_Name == "Speed (MHz)") {
											$Speed_MHz = $Attribute_Value;
										}
										if($Attribute_Name == "Memory (GB)") {
											$Memory_GB = $Attribute_Value;
										}
										if($Attribute_Name == "Memory Slots Count") {
											$Memory_Slots_Count = $Attribute_Value;
										}
										if($Attribute_Name == "Memory Slots Used") {
											$Memory_Slots_Used = $Attribute_Value;
										}
										if($Attribute_Name == "Power Supplies") {
											$Power_Supplies = $Attribute_Value;
										}
										if($Attribute_Name == "Nic Count") {
											$Nic_Count = $Attribute_Value;
										}
									}

									echo '<td>'. $Management_IP .'</td>';
									echo '<td>'. $RAC_IP .'</td>';
									echo '<td>'. $RAC_Firmware .'</td>';
									echo '<td>'. $Product .'</td>';
									echo '<td>'. $Version .'</td>';
									echo '<td>'. $Build .'</td>';
									echo '<td>'. $Update .'</td>';
									echo '<td>'. $Patch .'</td>';
									echo '<td>'. $Make .'</td>';
									echo '<td>'. $Model .'</td>';
									echo '<td>'. $SN .'</td>';
									echo '<td>'. $BIOS .'</td>';
									echo '<td>'. $BIOS_Release_Date .'</td>';
									echo '<td>'. $CPU_Model .'</td>';
									echo '<td>'. $CPU_Count .'</td>';
									echo '<td>'. $CPU_Core_Total .'</td>';
									echo '<td>'. $Speed_MHz .'</td>';
									echo '<td>'. $Memory_GB .'</td>';
									echo '<td>'. $Memory_Slots_Count .'</td>';
									echo '<td>'. $Memory_Slots_Used .'</td>';
									echo '<td>'. $Power_Supplies .'</td>';
									echo '<td>'. $Nic_Count .'</td>';
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