# Common Function
function AbortCANCELLEDTests{
	$query = "select * from testrun where Status_ID like 10"
	$CANCELLEDTestData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
	$CANCELLEDTestDataCount = $CANCELLEDTestData.Count
	if($CANCELLEDTestDataCount -ne 0) {
		foreach ($CANCELLEDTest in $CANCELLEDTestData){
			$TestRun_ID = $CANCELLEDTest.ID
			$TestRun_name = $CANCELLEDTest.Name
			write-log -Message "The Queue Manager is Aborting Test: ${TestRun_name}." -Logfile $logfile -LogLevel $logLevel -MsgType INFO
			# Update any Not Complete Test to Aborted
			$query = "update testrun set Status_ID=9 where ID = $TestRun_ID"
			Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
		}
		# Finished Aborting CANCELLED tests
		write-log -Message "The Queue Manager is finished aborting CANCELLED tests." -Logfile $logfile -LogLevel $logLevel -MsgType INFO
	}
	# There were no CANCELLED tests.
	write-log -Message "There were no CANCELLED tests." -Logfile $logfile -LogLevel $logLevel -MsgType INFO
}

#=======================================================================================
function AbortNotCompleteTests {
	$query = "select * from testrun where Status_ID not in ('5','6','7','9')"
	$RunningTestData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
	$RunningTestDataCount = $RunningTestData.Count
	if($RunningTestDataCount -ne 0) {
		foreach ($RunningTest in $RunningTestData){
			$TestRun_ID = $RunningTest.ID
			$TestRun_name = $RunningTest.Name
			write-log -Message "The Queue Manager is Aborting Test: ${TestRun_name}." -Logfile $logfile -LogLevel $logLevel -MsgType INFO
			# Update any Not Complete Test to Aborted
			$query = "update testrun set Status_ID=9 where ID = $TestRun_ID"
			Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
		}
		# Finished Aborting non-complete tests
		write-log -Message "The Queue Manager is finished aborting non-complete tests." -Logfile $logfile -LogLevel $logLevel -MsgType INFO
	}
	# There were no Non-Complete tests.
	write-log -Message "There were no non-complete tests." -Logfile $logfile -LogLevel $logLevel -MsgType INFO
}

#=======================================================================================
function ReviewSubmittedTests {
	# Keep in mind this is just to Assign and Test, not Start one!
	# Get a list of all of the Submitted Tests
	write-log -Message "Reviewing list of all Submitted tests." -Logfile $logfile -LogLevel $logLevel -MsgType INFO
	$query = "select * from testrun where Status_ID = 5"
	$SubmittedTestData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
	foreach($SubmittedTest in $SubmittedTestData) {
		# Get the test info! (Hypervisor_Type_ID,Workflow_ID)
		$Test_ID = $SubmittedTest.ID
		$TestRun_Name = $SubmittedTest.Name
		# Query the DB to find any Available sequence 
		write-log -Message "Assigning test: $Test_ID name: $TestRun_Name to TestRun Manager: $testRun_Manager_ID" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		$query = "update testrun set Status_ID = 6 where ID = '$Test_ID'"
		Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
	}
	# No more Tests to reviewing
	write-log -Message "Finished reviewing Submitted Tests" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
}

#=======================================================================================
function AssignQueuedTests {
	# Keep in mind this is just to Assign Tests, not Start one!
	# Get a list of all of the Queued Tests
	write-log -Message "Reviewing list of all Queued tests." -Logfile $logfile -LogLevel $logLevel -MsgType INFO
	$query = "select * from testrun where Status_ID = 6"
	$QueuedTestData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
	foreach($QueuedTest in $QueuedTestData) {
		# Get the Tests info! (Hypervisor_Type_ID,Workflow_ID)
		$Test_ID = $QueuedTest.ID
		$TestRun_Name = $QueuedTest.Name
		# Query the DB to find any Available sequence 
		write-log -Message "Determining if there are any TestRun Managers that are Up" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		$query = "select ID from testrun_manager where Status_ID like 2"
		$TestRunMGRData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
		$TestRunMGRcount = ($TestRunMGRData | Measure-Object).Count
		if($TestRunMGRcount -ne 0) {
			:ManagerLoop foreach($Manager in $TestRunMGRData){
				$testRun_Manager_ID = $Manager.ID
				write-log -Message "Assigning test: $Test_ID name: $TestRun_Name to TestRun Manager: $testRun_Manager_ID" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
				$query = "update testrun set Status_ID = 7, TestRun_Manager_ID = '$testRun_Manager_ID' where ID = '$Test_ID'"
				Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
				break ManagerLoop
			}
		}
		# No available TestRun Managers
		write-log -Message "There are no TestRun Managers Available" -Logfile $logfile -LogLevel $logLevel -MsgType INFO

	}
	# No more Tests to reviewing
	write-log -Message "Finished reviewing Queued Tests" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
}