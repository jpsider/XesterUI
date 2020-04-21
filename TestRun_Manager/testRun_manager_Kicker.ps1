# System Variables
set-ExecutionPolicy Bypass -Force

$MYINV = $MyInvocation
$SCRIPTDIR = split-path $MYINV.MyCommand.Path

# import logging, connection details, and mysql cmdlets.
. "$SCRIPTDIR\..\utilities\general-cmdlets.ps1"
. "$SCRIPTDIR\..\utilities\connection_details.ps1"
Import-Module PowerWamp,PowerLumber

#=======================================================================================
$KickerRunning = $true

#Start loop
do {
    # Delete Logs older than 3 days
    #Only needed if the TestRun Manager and Queue Manager are on separate machines. Simply uncomment the next line
    #Clear-Logs -Path "C:\XesterUI\TestRun_Manager" -DaysBack 3

    #check Status
    $query = "select ID,Status_ID,Wait from testrun_manager where ID like '$TestRunManagerID'"
	$ManagerData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
    $ManagerStatus = $ManagerData[0].Status_ID
    $ManagerWait = $ManagerData[0].Wait
    
    if($ManagerStatus -eq 3){
        #Check the status of the TestRunManager, if it starting up, start the TestRunManager process.
        write-output "The TestRunManager status is 'Starting Up', Lets get it going!"
        Start-Process -WindowStyle Normal powershell.exe -ArgumentList "-file TestRun_Manager.ps1"
        write-output "The TestRunManager was started."
    } else {
        # Take no action
        write-output "The TestRunManager status is not 'Starting Up', not taking action"        
    }
    write-output "Waiting $ManagerWait Seconds before checking again."
    pause $ManagerWait

} while ($KickerRunning = $true)
#=======================================================================================
#    _  _  _____                    ____       _             
#  _| || ||_   _|__  __ _ _ __ ___ | __ )  ___| | __ _ _   _ 
# |_  ..  _|| |/ _ \/ _` | '_ ` _ \|  _ \ / _ \ |/ _` | | | |
# |_      _|| |  __/ (_| | | | | | | |_) |  __/ | (_| | |_| |
#   |_||_|  |_|\___|\__,_|_| |_| |_|____/ \___|_|\__,_|\__, |
#                                                      |___/ 
#=======================================================================================