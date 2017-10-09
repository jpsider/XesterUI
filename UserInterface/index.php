<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	include 'components/database.php';
	if (!empty($_GET['HIDE_RECORD'])) {
		$testrun_id=$_GET['testrun_id'];
		// Update the database to set the test to hidden
		$sql = "UPDATE TESTRUN SET HIDDEN=1 where ID=$testrun_id";
		$pdo = Database::connect();
		$pdo->query($sql);
		Database::disconnect();
		//Send the user back to the same page (without get)
		header("Refresh:10 url=index.php");
	} else{
	}
		
	if (!empty($_GET['RERUN'])) {
		$testrun_id=$_GET['testrun_id'];
		// Update the database to set the test to hidden
		$sql = "UPDATE TESTRUN SET HIDDEN=1 where ID=$testrun_id";
		$pdo = Database::connect();
		$pdo->query($sql);
		Database::disconnect();
		//Submit new testrun
		$TestName=$_GET['TestName'];
		$System_ID=$_GET['System_ID'];
		if(!empty($_GET['Remediate'])){
			$Remediate=$_GET['Remediate'];
			$sql = "insert into testrun (Name,System_ID,STATUS_ID,RESULT_ID,Remediate,Hidden) VALUES ('$TestName','$System_ID','5','6','1',0)";
			$pdo = Database::connect();
			$pdo->query($sql);
			Database::disconnect();
			// Send the user back to the same page
			header("Refresh:0 url=index.php");		
		} else {
			$sql = "insert into testrun (Name,System_ID,STATUS_ID,RESULT_ID,Hidden) VALUES ('$TestName','$System_ID','5','6',0)";
			$pdo = Database::connect();
			$pdo->query($sql);
			Database::disconnect();
			// Send the user back to the same page
		}
			header("Refresh:0 url=index.php");
		//Send the user back to the same page (without get)
		header("Refresh:0 url=index.php");
	} else {
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
							<th>Hide</th>
							<th>ReRun</th>
							</tr>
						</thead>
						<tbody>
							<?php 
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
										. 'ts.Remediate, '
										. 'ts.Hidden, '													
										. 's.Status, '
										. 's.HtmlColor, '
										. 'r.Name as Result, '
										. 'r.HtmlColor as Result_Color, '
										. 'sys.SYSTEM_Name '
									. 'from TESTRUN ts '
									. 'join STATUS s on ts.Status_ID=s.ID '
									. 'join RESULTS r on ts.Result_ID=r.ID '
									. 'join systems sys on ts.System_ID=sys.ID '
									. 'where ts.Hidden=0 '
									. 'order by ts.ID DESC ';
	
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Name'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '>'. $row['Status'] . '</td>';
								echo '<td style=background-color:'. $row['Result_Color'] . '>'. $row['Result'] . '</td>';
								echo '<td>'. $row['SYSTEM_Name'] . '</td>';
								echo '<td>'. $row['Total_Tests'] . '</td>';
								echo '<td>'. $row['Failures'] . '</td>';
								echo '<td><form action="singleLogByName.php" method="get"><input type="hidden" name="Log_File" value='.$row['Log_file'].'><input type="submit" class="btn btn-info" value="View Log"></form></td>';
								echo '<td><form action="singleLogByName.php" method="get"><input type="hidden" name="Log_File" value='.$row['XML_file'].'><input type="submit" class="btn btn-info" value="View Log"></form></td>';	
								echo '<td>'. $row['Elapsed_Time'] . '</td>';			
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '<td width=250>';
								echo '<form action="SingleTestRun.php" method="get"><input type="hidden" name="testRun_id" value='.$row['ID'].'><input type="submit" class="btn btn-info" value="View TestRun"></form>';
								echo '</td>';
								echo '<td width=250>';
								echo '<form action="TestRunTestCases.php" method="get"><input type="hidden" name="testRun_id" value='.$row['ID'].'><input type="submit" class="btn btn-info" value="View TestCases"></form></td>';
								echo '<td><form action="index.php" method="get"><input type="hidden" name="HIDE_RECORD" value="TRUE"><input type="hidden" name="testrun_id" value='.$row['ID'].'><input type="submit" class="btn btn-primary" value="HIDE"></form></td>';								
								echo '<td><form action="index.php" method="get"><input type="hidden" name="RERUN" value="TRUE"><input type="hidden" name="TestName" value='.$row['Name'].'><input type="hidden" name="System_ID" value='.$row['System_ID'].'><input type="hidden" name="Remediate" value='.$row['Remediate'].'><input type="hidden" name="testrun_id" value='.$row['ID'].'><input type="submit" class="btn btn-warning" value="RERUN"></form></td>';
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
<?php
  }
?>
</html>