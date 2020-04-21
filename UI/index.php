<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
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
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
		<h3>TestRun Results</h3>
				<div class="row">
					<table id="example" class="table table-compact">
						<thead>
							<tr>
							<th>ID</th>
							<th>Test Name</th>
							<th>Status</th>
							<th>Result</th>
							<th>System_Name</th>
							<th>Total Tests</th>
							<th>Failures</th>
							<th>Logfile</th>
							<th>XML_File</th>
							<th>Elapsed_Time</th>
							<th>TestSuites</th>
							<th>TestCases</th>
							<th>Hide</th>
							<th>ReRun</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php
							//include 'components/database.php';
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
								echo '<td style=background-color:'. $row['HtmlColor'] . '><b>'. $row['Status'] . '</b></td>';
								echo '<td style=background-color:'. $row['Result_Color'] . '><b>'. $row['Result'] . '</b></td>';
								echo '<td>'. $row['SYSTEM_Name'] . '</td>';
								echo '<td>'. $row['Total_Tests'] . '</td>';
								echo '<td>'. $row['Failures'] . '</td>';
								echo '<td><form action="viewlog.php" method="get"><input type="hidden" name="Log_File" value='.$row['Log_file'].'><button class="btn btn-info btn-sm"><clr-icon class="is-solid" size="16" shape="list"></clr-icon> View Log</button></form></td>';
								echo '<td><form action="viewlog.php" method="get"><input type="hidden" name="Log_File" value='.$row['XML_file'].'><button class="btn btn-yellow-outline btn-sm"><clr-icon class="is-solid" size="16" shape="clipboard"></clr-icon> View XML</button></form></td>';	
								echo '<td>'. $row['Elapsed_Time'] . '</td>';			
								echo '<td width=250>';
								echo '<form action="testsuites.php" method="get"><input type="hidden" name="testRun_id" value='.$row['ID'].'><button class="btn btn-info btn-sm"><clr-icon class="is-solid" size="16" shape="eye"></clr-icon> View TestRun</button></form>';
								echo '</td>';
								echo '<td width=250>';
								echo '<form action="testcases.php" method="get"><input type="hidden" name="testRun_id" value='.$row['ID'].'><button class="btn btn-white-outline btn-sm"><clr-icon class="is-solid" size="16" shape="scroll"></clr-icon> View TestCases</button></form></td>';
								echo '<td><form action="index.php" method="get"><input type="hidden" name="HIDE_RECORD" value="TRUE"><input type="hidden" name="testrun_id" value='.$row['ID'].'><button class="btn btn-orange-outline btn-sm"><clr-icon class="is-solid" size="16" shape="resize-down"></clr-icon> HIDE</button></form></td>';								
								echo '<td><form action="index.php" method="get"><input type="hidden" name="RERUN" value="TRUE"><input type="hidden" name="TestName" value='.$row['Name'].'><input type="hidden" name="System_ID" value='.$row['System_ID'].'><input type="hidden" name="Remediate" value='.$row['Remediate'].'><input type="hidden" name="testrun_id" value='.$row['ID'].'><button class="btn btn-warning-outline btn-sm"><clr-icon class="is-solid" size="16" shape="redo"></clr-icon> RERUN</button></form></td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
					</table>
		</div> <!-- End of Row -->
		
	</div><!-- End content-area -->
    <nav class="sidenav">
		<?php
			require_once 'components/Side_Bar.html';
		?>
	</nav>
</div><!-- End content-container (From Header) -->
</body>
<?php
	}
?>
</html>