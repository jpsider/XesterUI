# System Variables
set-ExecutionPolicy Bypass -Force
$logLevel = "INFO"

$MYINV = $MyInvocation
$SCRIPTDIR = split-path $MYINV.MyCommand.Path

# import logging, connection details, and mysql cmdlets.
. "$SCRIPTDIR\..\utilities\general-cmdlets.ps1"
. "$SCRIPTDIR\..\utilities\connection_details.ps1"
. "$SCRIPTDIR\manager-cmdlets.ps1"
Import-Module PowerWamp,PowerLumber
# Set Shell Title
$host.ui.RawUI.WindowTitle = "XesterUI Queue Manager"
#=======================================================================================
# Script Arguments
#=======================================================================================

# The manager Requires the following items to get started properly
$ManagerAction=$args[0]
# Set the log file for the Manager.
$LogFile = "C:\XesterUI\Queue_Manager\Queue_Manager.log"
$logStartDay = (get-date).DayOfWeek

# Rename any old Logfiles
if(Test-Path -Path $logfile){
	write-Host "Logfile exists, Creating new logfile on QueueManager Start up."
	$currentTime = Get-Date -Format HHmm
	$NewLogName = "queue_manager_$currentTime.log"
	Rename-Item -Path $logfile -NewName $NewLogName -Force -Confirm:$false
}

#=======================================================================================
# Queue Manager
#=======================================================================================
$ManagerRUNNING = $true
			
do {
	####################################
	# Log Check
	####################################
	# If its a new day, we should roll the log
	$currentDay = (get-date).DayOfWeek
	if($currentDay -ne $logStartDay){
		write-log -Message "Its a new day, time to start a new log file." -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		$logStartDay = (get-date).DayOfWeek
		$currentTime = Get-Date -Format HHmm
		$NewLogName = "queue_manager_$currentTime.log"
		Rename-Item -Path $logfile -NewName $NewLogName -Force -Confirm:$false
		write-log -Message "#######################################################" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
	}else{
		write-log -Message "It's not a new day, not new log file." -Logfile $logfile -LogLevel $logLevel -MsgType INFO
	}

	####################################
	# Status Check  
	####################################
	#Query the DB for the Manager Status
	write-log -Message "#######################################################" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
	write-log -Message "Querying the Database to determine Queue Manager Status" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
	$query = "select Status_ID,Wait,Log_File from queue_manager"
	$ManagerData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
	$ManagerStatus = $ManagerData[0].Status_ID
	$ManagerWait = $ManagerData[0].Wait
	write-log -Message "Manager Status is : ${ManagerStatus}" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
	write-log -Message "Manager Wait is : ${ManagerWait}" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
	Write-Log -Message "Updating HeartBeat" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
	$CurrentHeartBeat = get-date
	$query = "update queue_manager set heartbeat='$CurrentHeartBeat' where ID like '1'"
	Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString

	####################################
	# Set Status to Starting up  
	####################################
	if($ManagerAction -eq "START"){
		#Set the status of the Manager to "Starting_Up"
		write-log -Message "The Manager status is ${ManagerStatus}, Lets start it up!" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		$query = "update queue_manager set Status_ID = 3"
		Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
		# wait 10 seconds
		write-log -Message "Pausing 10 seconds" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		$ManagerAction = "Done"
		pause 10
	}

	####################################
	# Start-up Tasks 
	####################################
	if($ManagerStatus -eq 3){ #Starting_Up
		#No Test should be RUNNING when started.
		write-log -Message "The Manager status is ${ManagerStatus} : Starting_Up, While we are starting up, lets Abort any Not Complete Tests" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		#Abort all Tests that are cancelled.
		AbortCANCELLEDTests #Cancelled
		#Abort all tests that are not complete.
		AbortNotCompleteTests #Not Complete
		write-log -Message "There are no more Non-complete tests to Abort, Setting the Queue-Manager to 'Up'" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		#Set the status of the Manager to Available/Active
		$query = "update queue_manager set Status_ID = 2"
		Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
		# wait 10 seconds
		write-log -Message "Pausing 10 seconds" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		pause 10
	}

	####################################
	# Normal Operation Tasks 
	####################################
	if($ManagerStatus -eq 2){ # Up and Running
		#Abort any Test the Status of 'CANCELLED'
		AbortCANCELLEDTests
		# wait 10 seconds
		write-log -Message "Pausing 10 seconds" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		pause 10
		# Review Submitted Tests
		ReviewSubmittedTests
		# wait 5 seconds
		write-log -Message "Pausing 5 seconds" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		pause 5
		# Review Queued Test Suites
		AssignQueuedTests
		# wait 10 seconds
		write-log -Message "Pausing 10 seconds" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		pause 10
		# Wait the Full QueueManager Wait time.
		write-log -Message "Pausing ${ManagerWait} seconds" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		pause $ManagerWait
	}

	####################################
	# Shutdown Tasks 
	####################################
	if($ManagerStatus -eq 4){ #Shutting_down
		write-log -Message "The Manager status is ${ManagerStatus}: Shutting_down, While we are Shutting down, lets Abort any RUNNING tests" -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		#Abort any Test the Status of 'CANCELLED'
		AbortCANCELLEDTests
		#Abort all tests that are not complete.
		AbortNotCompleteTests #Not Complete
		#set status of Manager to "DOWN"
		write-log -Message "The Queue Manager Status is now being set to down, and the process will stop RUNNING." -Logfile $logfile -LogLevel $logLevel -MsgType INFO
		$query = "update queue_manager set Status_ID = 1"
		Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
		$ManagerRUNNING = $false
		return $ManagerRUNNING
	}

} while ($ManagerRUNNING -eq $true)