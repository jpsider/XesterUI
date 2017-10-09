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
        write-log -Message "The TestRunManager status is 'Starting Up', Lets get it going!" -OutputStyle consoleOnly
        Start-Process -WindowStyle Normal powershell.exe -ArgumentList "-file TestRun_Manager.ps1"
        write-log -Message "The TestRunManager was started." -OutputStyle consoleOnly
    } else {
        # Take no action
        write-log -Message "The TestRunManager status is not 'Starting Up', not taking action" -OutputStyle consoleOnly        
    }
    write-log -Message "Waiting $ManagerWait Seconds before checking again." -OutputStyle consoleOnly
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