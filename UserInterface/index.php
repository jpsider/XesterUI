<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<script>
    var reloading;
    var refresh_time = 30000;
    
    function checkReloading() {
        if (window.location.hash=="#autoreload") {
            reloading=setTimeout("window.location.reload();", refresh_time);
            document.getElementById("reloadCB").checked=true;
        }
    }
    
    function toggleAutoRefresh(cb) {
        if (cb.checked) {
            window.location.replace("#autoreload");
            reloading=setTimeout("window.location.reload();", refresh_time);
        } else {
            window.location.replace("#");
            clearTimeout(reloading);
        }
    }
    
    window.onload=checkReloading;
</script>
<script> 
	$(document).ready(function() {
		$('#example').dataTable( {
			"order":[[ 2, "desc" ]]
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
			<span><input type="checkbox" onclick="toggleAutoRefresh(this);" id="reloadCB"> Auto Refresh</span>
				<h3>XesterUI TestRun Results</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Status</th>
							<th>Result</th>
							<th>System_Name</th>
							<th>Total Tests</th>
							<th>Failures</th>
							<th>Logfile</th>
							<th>XML_File</th>
							<th>Elapsed_Time</th>
							<th>date_modified</th>
							<th>TestSuites</th>
							<th>TestCases</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select ts.ID, ' 
										. 'ts.Name, '
										. 'ts.Total_Tests, '
										. 'ts.Status_ID, '
										. 'ts.Result_ID, '
										. 'ts.System_ID, '	
										. 'ts.date_modified, '													
										. 'ts.Failures, '													
										. 'ts.Log_file, '													
										. 'ts.XML_file, '													
										. 'ts.Elapsed_Time, '													
										. 's.Status, '
										. 's.HtmlColor, '
										. 'r.Name as Result, '
										. 'r.HtmlColor as Result_Color, '
										. 'sys.SYSTEM_Name '
									. 'from TESTRUN ts '
									. 'join x_status s on ts.Status_ID=s.ID '
									. 'join X_result r on ts.Result_ID=r.ID '
									. 'join systems sys on ts.System_ID=sys.ID '
									. 'order by ts.ID DESC ';
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr><form action="SingleTestRun.php" method="get">';
								echo '<td><input type="hidden" name="testRun_id" value='.$row['ID'].'>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Name'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '>'. $row['Status'] . '</td>';
								echo '<td style=background-color:'. $row['Result_Color'] . '>'. $row['Result'] . '</td>';
								echo '<td>'. $row['SYSTEM_Name'] . '</td>';
								echo '<td>'. $row['Total_Tests'] . '</td>';
								echo '<td>'. $row['Failures'] . '</td>';
								echo '<td>'. $row['Log_file'] . '</td>';
								echo '<td>'. $row['XML_file'] . '</td>';			
								echo '<td>'. $row['Elapsed_Time'] . '</td>';			
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '<td width=250>';
								echo '<input type="submit" class="btn btn-info" value="View TestRun"></form>';
								echo '</td>';
								echo '<td width=250>';
								echo '<form action="TestRunTestCases.php" method="get"><input type="hidden" name="testRun_id" value='.$row['ID'].'><input type="submit" class="btn btn-info" value="View TestCases"></form>';
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