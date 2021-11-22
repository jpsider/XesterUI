<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['delete_Stacktraces'])) {
		include 'components/database.php';
		// Update the database to set the test to aborted
		$sql = "delete from stacktrace;";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Send the user back to the same page (without get)
		header("Refresh:0 url=cleanup.php");
	} elseif (!empty($_GET['delete_testCases'])) {
		include 'components/database.php';
		// Update the database to set the test to aborted
		$sql = "delete from testcases;";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Send the user back to the same page (without get)
		header("Refresh:0 url=cleanup.php");
	} elseif (!empty($_GET['delete_TestSuites'])) {
		include 'components/database.php';
		// Update the database to set the test to aborted
		$sql = "delete from testsuites;";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Send the user back to the same page (without get)
		header("Refresh:0 url=cleanup.php");
	} elseif (!empty($_GET['delete_TestRuns'])) {
		include 'components/database.php';
		// Update the database to set the test to aborted
		$sql = "delete from testrun;";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Send the user back to the same page (without get)
		header("Refresh:0 url=cleanup.php");
	} else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->

<h3>Database Cleanup</h3></br>
				<div class="row">
					<table class="table table-compact">
						<thead>
							<tr>
							<th>Step 1</th>
							<th>Step 2</th>
							<th>Step 3</th>
							<th>Step 4</th>
							</tr>
						</thead>
						<tbody>
                            <tr>
                                <td>Cleanup all Stack traces</td>
                                <td>Cleanup all Test Cases</td>
                                <td>Cleanup all Test Suites</td>
                                <td>Cleanup all TestRuns</td>
                            </tr>
                            <tr>
                                <td><form action="cleanup.php" method="get"><input type="hidden" name="delete_Stacktraces" value="3"><button class="btn btn-warning-outline btn-sm"><clr-icon class="is-solid" shape="key" size="16"></clr-icon>Delete Stack Traces</button></form></td>
                                <td><form action="cleanup.php" method="get"><input type="hidden" name="delete_testCases" value="3"><button class="btn btn-warning-outline btn-sm"><clr-icon class="is-solid" shape="key" size="16"></clr-icon> Delete Test Cases</button></form></td>
                                <td><form action="cleanup.php" method="get"><input type="hidden" name="delete_TestSuites" value="3"><button class="btn btn-warning-outline btn-sm"><clr-icon class="is-solid" shape="key" size="16"></clr-icon> Delete Test Suites</button></form></td>
                                <td><form action="cleanup.php" method="get"><input type="hidden" name="delete_TestRuns" value="3"><button class="btn btn-warning-outline btn-sm"><clr-icon class="is-solid" shape="key" size="16"></clr-icon> Delete Test runs</button></form></td>
                            </tr>
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