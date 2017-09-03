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
				<h3>vDocumentation - Host Datastore Info</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Target_ID</th>
								<th>HostName</th>					
								<th>DataStore Name</th>
								<th>Device Name</th>
								<th>Canonical Name</th>
								<th>LUN</th>
								<th>Type</th>
								<th>Datastore Cluster</th>
								<th>Capacity (GB)</th>
								<th>Provisioned Space (GB)</th>
								<th>Free Space (GB)</th>
								<th>Transport</th>
								<th>Mount Point</th>
								<th>Multipath Policy</th>
								<th>File System Version</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$DataStore_Name = "null";
							$Device_Name = "null";
							$Canonical_Name = "null";
							$LUN = "null";
							$Type = "null";
							$Datastore_Cluster = "null";
							$Capacity_GB = "null";
							$Provisioned_Space_GB = "null";
							$Free_Space_GB = "null";
							$Transport = "null";
							$Mount_Point = "null";
							$Multipath_Policy = "null";
							$File_System_Version = "null";
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
									. "where inv.Target_ID=$Target_ID and aa.Attribute_Type_ID=6";

									echo '<tr>';
									echo '<td>'. $Target_ID .'</td>';
									echo '<td>'. $Hostname .'</td>';						

									foreach ($pdo->query($sql1) as $row1) {	
										$Attribute_Name = $row1['Attribute_Name'];
										$Attribute_Value =  $row1['Attribute_Value'];			
										if($Attribute_Name == "DataStore Name") {
											$DataStore_Name = $Attribute_Value;
										}
										if($Attribute_Name == "Device Name") {
											$Device_Name = $Attribute_Value;
										}
										if($Attribute_Name == "Canonical Name") {
											$Canonical_Name = $Attribute_Value;
										}
										if($Attribute_Name == "LUN") {
											$LUN = $Attribute_Value;
										}
										if($Attribute_Name == "Type") {
											$Type = $Attribute_Value;
										}
										if($Attribute_Name == "Datastore Cluster") {
											$Datastore_Cluster = $Attribute_Value;
										}
										if($Attribute_Name == "Capacity (GB)") {
											$Capacity_GB = $Attribute_Value;
										}
										if($Attribute_Name == "Provisioned Space (GB)") {
											$Provisioned_Space_GB = $Attribute_Value;
										}
										if($Attribute_Name == "Free Space (GB)") {
											$Free_Space_GB = $Attribute_Value;
										}
										if($Attribute_Name == "Transport") {
											$Transport = $Attribute_Value;
										}
										if($Attribute_Name == "Mount Point") {
											$MountPoint = $Attribute_Value;
										}
										if($Attribute_Name == "Multipath Policy") {
											$Multipath_Policy = $Attribute_Value;
										}
										if($Attribute_Name == "File System Version") {
											$File_System_Version = $Attribute_Value;
										}
									}

									echo '<td>'. $DataStore_Name .'</td>';
									echo '<td>'. $Device_Name .'</td>';
									echo '<td>'. $Canonical_Name .'</td>';
									echo '<td>'. $LUN .'</td>';
									echo '<td>'. $Type .'</td>';
									echo '<td>'. $Datastore_Cluster .'</td>';
									echo '<td>'. $Capacity_GB .'</td>';
									echo '<td>'. $Provisioned_Space_GB .'</td>';
									echo '<td>'. $Free_Space_GB .'</td>';
									echo '<td>'. $Transport .'</td>';
									echo '<td>'. $Mount_Point .'</td>';
									echo '<td>'. $Multipath_Policy .'</td>';
									echo '<td>'. $File_System_Version .'</td>';
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