#=======================================================================================
# System Variables
set-ExecutionPolicy Bypass -Force

$MYINV = $MyInvocation
$SCRIPTDIR = split-path $MYINV.MyCommand.Path

# import logging, connection details, and mysql cmdlets.
. "$SCRIPTDIR\..\utilities\general-cmdlets.ps1"
. "$SCRIPTDIR\..\utilities\connection_details.ps1"
. "$SCRIPTDIR\manager-cmdlets.ps1"
Import-Module PowerWamp,PowerLumber

#=======================================================================================
$KickerRunning = $true

#Start loop
do {
    # Delete Logs older than 3 days
    Clear-Logs -Path "C:\XesterUI\Queue_Manager" -DaysBack 3
    Clear-Logs -Path "C:\XesterUI\TestRun_Manager" -DaysBack 3
    Clear-Logs -Path "C:\XesterUI\TestRuns" -DaysBack 3

    #check Status
    $query = "select Status_ID,Wait,Log_File from queue_manager"
	$ManagerData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
    $ManagerStatus = $ManagerData[0].Status_ID
    $ManagerWait = $ManagerData[0].Wait
    
    if($ManagerStatus -eq 3){
        #Check the status of the Manager, if it starting up, start the manager process.
        write-log -Message "The Manager status is 'Starting Up', Lets get it going!" -OutputStyle consoleOnly
        Start-Process -WindowStyle Normal powershell.exe -ArgumentList "-file queueManager.ps1"
        write-log -Message "The Manager was started." -OutputStyle consoleOnly
    } else {
        #Check the status of the Manager, if it starting up, start the manager process.
        write-log -Message "The Manager status is not 'Starting Up', not taking action" -OutputStyle consoleOnly        
    }
    write-log -Message "Waiting $ManagerWait Seconds before checking again." -OutputStyle consoleOnly
    pause $ManagerWait

} while ($KickerRunning = $true)