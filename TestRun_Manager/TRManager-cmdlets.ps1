#=======================================================================================
# __  __         _            _   _ ___ 
# \ \/ /___  ___| |_ ___ _ __| | | |_ _|
#  \  // _ \/ __| __/ _ \ '__| | | || | 
#  /  \  __/\__ \ ||  __/ |  | |_| || | 
# /_/\_\___||___/\__\___|_|   \___/|___|
#                                       
#=======================================================================================
function CancelRunningTestRuns() {
    # Query the DB for any Tests that are not Complete or Assigned for this TestRun Manager.
    write-log -Message "Cancelling running tests" -Logfile $logfile
    $query = "select * from testrun where Status_ID not in ('7','9') and TestRun_Manager_ID = '$TestRunManagerID'"
    $RunningTestData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
    if ($RunningTestData -ne $null) {
        foreach ($RunningTest in $RunningTestData) {
            $TestRun_ID = $RunningTest.ID
            $TestRun_NAME = $RunningTest.Name
            write-log -Message "Setting TestRun: $TestRun_NAME to Complete." -Logfile $logfile
            $query = "update testrun set Status_ID = 9, Result_ID = 5 where ID = $TestRun_ID"
            Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
        }
    } else {
        write-log -Message "No Test were running. moving on!" -Logfile $logfile
    }
    write-log -Message "Finished reviewing Running Test for this TestRun Manager" -Logfile $logfile
}
#=======================================================================================
function RequeueAssignedTests() {
    #Query the DB for any tests assigned to this TestRun Mgr.
    write-log -Message "Re-Queueing Assigned Tests before we shut this TestRun Manager down" -Logfile $logfile
    $query = "select * from testrun where Status_ID = 7 and TestRun_Manager_ID = '$TestRunManagerID'"
    $RequeueData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
    if ($RequeueData -ne $null) {
        foreach($requeueTest in $RequeueData) {
            $TestRun_ID = $requeueTest.ID
            $TestRun_NAME = $requeueTest.Name
            write-log -Message "Setting Test: $TestRun_NAME to Queued." -Logfile $logfile
            $query = "update testrun set Status_ID = 6 where ID = $TestRun_ID"
            Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString            
        }
    } else {
        write-log -Message "No Tests need to be Re-Queued" -Logfile $logfile
    }
    write-log -Message "Finished reviewing Requeued Tests." -Logfile $logfile
}
#=======================================================================================
function StartAssignedTests() {
    # Check to see if the Testrun Manager is at its Max
    $query = "select * from testrun where TestRun_Manager_ID like '$TestRunManagerID' and Status_ID like 8"
    $RunningTestData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
    $RunningTestCount = $RunningTestData.Count
    if ($RunningTestCount -le $TestRunMGR_Max_Concurrent) {
        write-log -Message "The TestRun Manager: $TestRunManagerID is not Maxed, lets look at the Assigned Tests" -Logfile $logfile
        # Get the list of Assigned Tests
        $query = "select ID,Name,System_ID from testrun where TestRun_Manager_ID like '$TestRunManagerID' and Status_ID like 7"
        $AssignedTestData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
        $AssignedTestCount = $AssignedTestData.Count
        if ($AssignedTestCount -ne $null){
            # Get the Test info
            write-log -Message "Loop through each Test to see if we can start the test." -Logfile $logfile
            foreach ($assignedTest in $AssignedTestData) {
                $TestRun_ID = $assignedTest.ID
                $TestRun_NAME = $assignedTest.Name
                $System_ID = $assignedTest.System_ID              
                #Query the DB for System Information                
                $query = "select Config_File from systems where ID like '$System_ID'"
                $SystemData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
                $Config_Path = $SystemData.Config_File
                
                # Start the Workflow Script passing in needed data.
                write-log -Message "Starting Test: $TestRun_ID TestName: $TestRun_NAME with Config path: $Config_Path" -Logfile $logfile
                Start-Process -WindowStyle Normal powershell.exe -ArgumentList "-file $workflow_script", "$TestRun_ID"
                pause 5
                # Breaking ensures we do not over load the TestRun Manager.
                Break
            }
        } else {
            # There are no assigned tests exit loop.
            write-log -Message "There are no assigned Tests to this TestRun Manager right now." -Logfile $logfile
        }
    } else {
        # The TestRun Manager cannot run anymore tests exit loop.
        write-log -Message "The TestRun Manager is running the maximum number of Tests right now." -Logfile $logfile
    }
    write-log -Message "The TestRun Manager has finished reviewing Assigned Tests" -Logfile $logfile
}
#=======================================================================================
#    _  _  _____                    ____       _             
#  _| || ||_   _|__  __ _ _ __ ___ | __ )  ___| | __ _ _   _ 
# |_  ..  _|| |/ _ \/ _` | '_ ` _ \|  _ \ / _ \ |/ _` | | | |
# |_      _|| |  __/ (_| | | | | | | |_) |  __/ | (_| | |_| |
#   |_||_|  |_|\___|\__,_|_| |_| |_|____/ \___|_|\__,_|\__, |
#                                                      |___/ 
#=======================================================================================