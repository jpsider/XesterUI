#=======================================================================================
# __  __         _            _   _ ___ 
# \ \/ /___  ___| |_ ___ _ __| | | |_ _|
#  \  // _ \/ __| __/ _ \ '__| | | || | 
#  /  \  __/\__ \ ||  __/ |  | |_| || | 
# /_/\_\___||___/\__\___|_|   \___/|___|
#                                       
#=======================================================================================
# System Variables
set-ExecutionPolicy Bypass -Force

$MYINV = $MyInvocation
$SCRIPTDIR = split-path $MYINV.MyCommand.Path

# import logging, connection details, and mysql cmdlets.
. "$SCRIPTDIR\..\utilities\general-cmdlets.ps1"
. "$SCRIPTDIR\..\utilities\connection_details.ps1"
. "$SCRIPTDIR\TRManager-cmdlets.ps1"
Import-Module PowerWamp,PowerLumber
$workflow_script = "C:\OPEN_PROJECTS\XesterUI\Workflows\Execute_Vester.ps1"
# Set Shell Title
$host.ui.RawUI.WindowTitle = "TestRun_Manager"
#=======================================================================================
# Script Arguments
#=======================================================================================
# The manager Requires the following items to get started properly
$ManagerAction=$args[0]
# Set the log file for the Manager.
$logfile = "C:\XesterUI\TestRun_Manager\TestRun_Manager.log"
$logStartDay = (get-date).DayOfWeek

# Rename any old Logfiles
if(Test-Path -Path $logfile){
	write-Host "Logfile exists, Creating new logfile on TestRunManager Start up."
	$currentTime = Get-Date -Format HHmm
	$NewLogName = "TestRun_manager_$currentTime.log"
	Rename-Item -Path $logfile -NewName $NewLogName -Force -Confirm:$false
}

#=======================================================================================
# TestRun_Manager
#=======================================================================================
$TestRun_ManagerRUNNING = $true

do {
	####################################
	# Log Check
	####################################
	# If its a new day, we should roll the log
	$currentDay = (get-date).DayOfWeek
	if($currentDay -ne $logStartDay){
		write-log -Message "Its a new day, time to start a new log file." -Logfile $logfile
		$logStartDay = (get-date).DayOfWeek
		$currentTime = Get-Date -Format HHmm
		$NewLogName = "TestRun_manager_$currentTime.log"
		Rename-Item -Path $logfile -NewName $NewLogName -Force -Confirm:$false
		write-log -Message "#######################################################" -Logfile $logfile
	}else{
		write-log -Message "It's not a new day, no new log file." -Logfile $logfile
	}

	####################################
	# Status Check  
	####################################
	#Query the DB for the TestRun Manager Status
	write-log -Message "#######################################################" -Logfile $logfile
	write-log -Message "Querying the Database to determine TestRun Manager Status" -Logfile $logfile
	$query = "select ID,Status_ID,Wait,Log_File,Max_Concurrent from testrun_manager where ID like '$TestRunManagerID'"
	$TestRunManagerData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
	$TestRunManagerStatus = $TestRunManagerData[0].Status_ID
	$TestRunManagerWait = $TestRunManagerData[0].Wait
	#$logfile = $TestRunManagerData[0].Log_File
	$TestRunMGR_Max_Concurrent = $TestRunManagerData[0].Max_Concurrent
	write-log -Message "TestRun Manager ID is : ${TestRunManagerID}" -Logfile $logfile
	write-log -Message "TestRun Manager Status is : ${TestRunManagerStatus}" -Logfile $logfile
	write-log -Message "TestRun Manager Wait is : ${TestRunManagerWait}" -Logfile $logfile
	write-log -Message "TestRun Manager Max_Concurrent is : ${TestRunMGR_Max_Concurrent}" -Logfile $logfile
	Write-Log -Message "Updating HeartBeat" -Logfile $logfile
	$CurrentHeartBeat = get-date
	$query = "update testrun_manager set heartbeat='$CurrentHeartBeat' where ID like '$TestRunManagerID'"
	Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString

	####################################
	# Set Status to Starting up  
	####################################
    if($ManagerAction -eq "START"){
        #Set the status of the TestRun Manager to "Starting_Up"
		write-log -Message "The Manager status is ${TestRunManagerStatus}, Lets start it up!" -Logfile $logfile
		$query = "update testrun_manager set Status_ID = 3 where ID like '$TestRunManagerID'"
		Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
		$ManagerAction = "Done"
        # wait 5 seconds
		write-log -Message "Pausing 5 seconds" -Logfile $logfile
        pause 5
    }

	####################################
	# Start-up Tasks 
	####################################
    if($TestRunManagerStatus -eq 3){ #Starting_Up
		write-log -Message "Begining Startup Tasks for TestRun Manager. Cancelling any running tests." -Logfile $logfile
        # Cancel any Running SUT's for this TestRun Manager
        CancelRunningTestRuns
		# wait 5 seconds
		write-log -Message "Pausing 5 seconds" -Logfile $logfile
		pause 5
        # Set the status of the TestRun Manager to Up
        $query = "update testrun_manager set Status_ID = 2 where ID = '$TestRunManagerID'"
		Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
    }

	####################################
	# Normal Operation Tasks 
	####################################
    if($TestRunManagerStatus -eq 2){ # Up and Running
		write-log -Message "#############################################" -Logfile $logfile
		write-log -Message "TestRun Manager is running, Lets review Assigned Tests" -Logfile $logfile
        # Start Assigned Sut
        StartAssignedTests
		# wait 5 seconds
		write-log -Message "Pausing 5 seconds" -Logfile $logfile
		pause 5        
        write-log -Message "Pausing $TestRunManagerWait seconds." -Logfile $logfile
        pause $TestRunManagerWait
    }

	####################################
	# Shutdown Tasks 
	####################################
    if($TestRunManagerStatus -eq 4){ #Shutting_down
        # Cancel any Running SUT's for this TestRun Manager
		write-log -Message "Beginning Shutdown Procedures, Cancelling any running Tests" -Logfile $logfile
        CancelRunningTestRuns
		# wait 5 seconds
		write-log -Message "Pausing 5 seconds" -Logfile $logfile
		pause 5
		#set status of TestRun Manager to "DOWN"
		write-log -Message "The TestRun Manager Status is now being set to down, and the process will stop RUNNING." -Logfile $logfile
		$query = "update testrun_manager set Status_ID = 1 where ID = '$TestRunManagerID'"
		Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
        # Requeue any Assigned SUT's
        RequeueAssignedTests
		$TestRun_ManagerRUNNING = $false
		return $TestRun_ManagerRUNNING        
    }

} while ($TestRun_ManagerRUNNING -eq $true)
#=======================================================================================
#    _  _  _____                    ____       _             
#  _| || ||_   _|__  __ _ _ __ ___ | __ )  ___| | __ _ _   _ 
# |_  ..  _|| |/ _ \/ _` | '_ ` _ \|  _ \ / _ \ |/ _` | | | |
# |_      _|| |  __/ (_| | | | | | | |_) |  __/ | (_| | |_| |
#   |_||_|  |_|\___|\__,_|_| |_| |_|____/ \___|_|\__,_|\__, |
#                                                      |___/ 
#=======================================================================================