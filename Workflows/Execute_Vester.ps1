#=======================================================================================
# __  __         _            _   _ ___ 
# \ \/ /___  ___| |_ ___ _ __| | | |_ _|
#  \  // _ \/ __| __/ _ \ '__| | | || | 
#  /  \  __/\__ \ ||  __/ |  | |_| || | 
# /_/\_\___||___/\__\___|_|   \___/|___|
#                                       
#=======================================================================================
# This Script will Connect to the Database to Gather Test information, 
#   Then execute a Vester test with the given information.
#=======================================================================================
# System Variables
set-ExecutionPolicy Bypass -Force

$MYINV = $MyInvocation
$SCRIPTDIR = split-path $MYINV.MyCommand.Path
# import logging, connection details, and mysql cmdlets.
. "$SCRIPTDIR\..\utilities\general-cmdlets.ps1"
. "$SCRIPTDIR\..\utilities\connection_details.ps1"
Import-Module "C:\OPEN_PROJECTS\PowerLumber\PowerLumber.psm1"
Import-Module "C:\OPEN_PROJECTS\PowerWamp\powerWamp.psm1"
Import-Module Vester
Get-Module -ListAvailable vmware* | Import-Module

#=======================================================================================
# Gather Args
$TestRun_ID=$args[0]

#=======================================================================================
# Mark the test as Running
$query = "update testrun set STATUS_ID = 8 where ID like '$TestRun_ID'"
Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString

Pause 3

#=======================================================================================
# Get the Info from the DB
$query = "Select tr.ID, 
            tr.Name, 
            tr.System_ID, 
            tr.Remediate, 
            sys.System_Name, 
            sys.Config_File
            from TESTRUN tr
            join SYSTEMS sys on tr.System_ID=sys.ID 
            where tr.ID like '$TestRun_ID'"
$TestRunData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
$TestRun_Name = $TestRunData.Name
$TestRun_System_ID = $TestRunData.System_ID
$TestRun_Remediate = $TestRunData.Remediate
$TestRun_System_Name = $TestRunData.System_Name
$TestRun_Config_File = $TestRunData.Config_File

#=======================================================================================
# create a results Directory and start a log file
$UniqueIdentity = $TestRun_Name + "_" + $TestRun_ID
$resultsDir = "c:\XesterUI\TestRuns\$UniqueIdentity"
$Logfile = "$resultsDir\testrun.log"
$resultsFile = "$resultsDir\results.xml"

write-log -Message "Add the Log file to the DB." -Logfile $logfile
$DBLogPath = $LogFile.Replace('\',"\\")
$query = "update TESTRUN set Log_file = '$DBLogPath' where ID like '$TestRun_ID'"
Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString

write-log -Message "Starting TestRun: $TestRun_Name on System: $TestRun_System_Name Remediate: $TestRun_Remediate Config_File: $TestRun_Config_File" -Logfile $logfile

Pause 3

#=======================================================================================
# Get the vCenter Information from the System_ID
write-log -Message "Retrieving vCenter information from DB." -Logfile $logfile
$query = "select tg.ID, 
            tg.Target_Name, 
            tg.Target_Type_ID,
            tg.IP_Address,
            tg.Password_ID,
            tg.System_ID,
            pw.Username,
            pw.password
            from TARGETS tg
            join PASSWORDS pw on tg.Password_ID=pw.ID
            where tg.system_ID like '$TestRun_System_ID' and tg.Target_Type_ID like '1'"
$vCenterData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
$vc_name = $vCenterData.name
$vc_ip = $vCenterData.IP_Address
$vc_un = $vCenterData.username
$vc_pw = $vCenterData.password

#=======================================================================================
# Connect to the vCenter
write-log -Message "Connecting to vCenter" -Logfile $logfile
Connect-VIServer -Server $vc_ip -User $vc_un -Password $vc_pw

# TODO - Try/catch block in future. or write cmdlets file 

#=======================================================================================
# Execute and wait for the Vester test to finish Saving the XML file to the results Directory
write-log -Message "Executing Vester Test." -Logfile $logfile
if($TestRun_Remediate -eq 1){
    write-log -Message "Executing Vester Test config file: $TestRun_Config_File with the Remediate Flag." -Logfile $logfile
    Invoke-Vester -config $TestRun_Config_File -XMLOutputFile $resultsFile -Remediate
}else{
    # Do not Remediate
    write-log -Message "Executing Vester Test config file: $TestRun_Config_File with NO Remediate Flag." -Logfile $logfile
    Invoke-Vester -config $TestRun_Config_File -XMLOutputFile $resultsFile
}

#=======================================================================================
# Disconnect from the vCenter
write-log -Message "Disconnecting from vCenter." -Logfile $logfile
Disconnect-VIServer -Server $vc_ip -Confirm:$false

#=======================================================================================
# Parse the XML file to input the results to the Database
write-log -Message "Parsing the results and updating the DB." -Logfile $logfile

write-log -Message "Import the XML file and add the Path to the DB." -Logfile $logfile
$DBXMLPath = $resultsFile.Replace('\',"\\")
$query = "update TESTRUN set XML_File = '$DBXMLPath' where ID like '$TestRun_ID'"
Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString

# TODO - Should probably verify it exists first.
[xml]$xmlData = get-content "$resultsFile"

$testRundata = $xmlData.'test-results'
$TotalTests = $testRundata.total
$TotalErrors = $testRundata.errors
$TotalFailures = $testRundata.failures
$TotalNotRun = $testRundata.("not-run")
$TotalInconclusive = $testRundata.inconclusive 
$TotalIgnored = $testRundata.ignored
$TotalSkipped = $testRundata.skipped 
$TotalInvalid = $testRundata.invalid
$ElapsedTime = $testRundata.time

if($TotalFailures -ne 0) {
    $Result = 2
} else {
    $Result = 1
}

write-log -Message "Adding top level TestRun results to DB." -Logfile $logfile
$query = "update TESTRUN set RESULT_ID='$Result',Total_Tests='$TotalTests',
    Errors='$TotalErrors',Failures='$TotalFailures',NotRun='$TotalNotRun',inconclusive='$TotalInconclusive',
    Ignored='$TotalIgnored',Skipped='$TotalSkipped',Invalid='$TotalInvalid',Elapsed_Time='$ElapsedTime' where ID like $testRun_id"
Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString

write-log -Message "------TEST RUN DATA-------" -Logfile $logfile
write-log -Message "TestRun_ID $TestRun_ID" -Logfile $logfile
write-log -Message "Total Tests $TotalTests" -Logfile $logfile
write-log -Message "Errors $TotalErrors" -Logfile $logfile
write-log -Message "Failures $TotalFailures" -Logfile $logfile
write-log -Message "NotRun $TotalNotRun" -Logfile $logfile
write-log -Message "Inclusive $TotalInconclusive" -Logfile $logfile
write-log -Message "Ignored $TotalIgnored" -Logfile $logfile
write-log -Message "Skipped $TotalSkipped" -Logfile $logfile
write-log -Message "Invalid $TotalInvalid" -Logfile $logfile
write-log -Message "Elapsed Time $ElapsedTime" -Logfile $logfile
write-log -Message "------ END TEST RUN DATA-------" -Logfile $logfile

write-log -Message "Processing TestSuite/TestCase data." -Logfile $logfile
$TestSuites = @($xmlData.'test-results'.'test-suite'.results.'test-suite')
$TScount = 0
$TCcount = 0
foreach($testSuite in $TestSuites) {
    write-log -Message "----------START TestSuite----------" -Logfile $logfile
    $TSstring = $testSuite.description
    $TSparts = $TSstring.split(" ",3)
    $TS_NAME = $TSparts[2]
    $TS_Elapsed_Time = $testSuite.time
    $TS_Asserts = $testSuite.asserts
    $TS_Target_Type = $TSparts[0]
    $TS_Result_Name = $testSuite.result
    write-log -Message "Target Type: $TS_Target_Type" -Logfile $logfile
    write-log -Message "TestSuite Name: $TS_NAME" -Logfile $logfile
    write-log -Message "TestSuite Result: $TS_Result_Name" -Logfile $logfile
    write-log -Message "Elapsed Time: $TS_Elapsed_Time" -Logfile $logfile
    write-log -Message "Asserts: $TS_Asserts" -Logfile $logfile

    if($testSuite.result -eq "Success") {
        $TS_Result = 1
    } elseif($testSuite.result -eq "Failure") {
        $TS_Result = 2
    } else {
        $TS_Result = 3
    }

    $testcases = @($testSuite.results.'test-case')
    $testcases_Count = $testcases.count

    write-log -Message "Adding top level TestSuite results to DB." -Logfile $logfile
    $query = "insert into TESTSUITES (Name,TestRun_ID,STATUS_ID,RESULT_ID,Elapsed_Time,TestCase_Count,Asserts) VALUES ('$TS_NAME','$TestRun_ID',9,'$TS_Result','$TS_Elapsed_Time','$testcases_Count','$TS_Asserts')"
    $TestSuite_ID = @(Invoke-MySQLInsert -Query $query -ConnectionString $MyConnectionString)[1]
    
    foreach($testcase in $testcases){
        # Get the TestCase information
        $string = $testcase.description
        $parts = $string.split(" ",4)
        $TC_NAME = $parts[3]
        $TC_Elapsed_Time = $testcase.time
        $TC_Asserts = $testcase.asserts
        $TC_Result_Name = $testcase.result
        $TC_Target_type = $parts[0]
        $target = $parts[1]

        #Validate that a Target exists in the DB, if not, create it for the system.
        $query = "select ID,Target_Name,Target_Type_ID,System_ID from TARGETS where Target_Name like '$target' and System_ID like '$TestRun_System_ID'"
        $Target_Data = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
        if($Target_Data.count -eq 0) {
            write-log -Message "Adding new target $target to System: $TestRun_System_ID DB." -Logfile $logfile
            $query = "insert into targets (Target_Name,Target_Type_ID,Status_ID,Password_ID,System_ID) VALUES ('$target',(select ID from Target_Types where name like '$TC_Target_type'),'11','1','$TestRun_System_ID')"
            Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
        }

        # Get the Target ID based on the name in the xml file.
        $query = "select ID from Targets where Target_Name like '$target' and System_ID like '$TestRun_System_ID'"
        $TC_TARGET_ID = (@(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)).ID

        write-log -Message "Testcase name: $TC_NAME" -Logfile $logfile
        write-log -Message "Testcase result: $TC_Result_Name" -Logfile $logfile
        write-log -Message "Target Type: $TC_Target_type" -Logfile $logfile
        write-log -Message "Target: $target" -Logfile $logfile
        write-log -Message "Elapsed Time: $TC_Elapsed_Time" -Logfile $logfile
        write-log -Message "Asserts: $TC_Asserts" -Logfile $logfile
        write-log -Message "Target ID: $TC_TARGET_ID" -Logfile $logfile
        #write-host "Hyphen:" $parts[2]
        if($testcase.result -eq "Success") {
            $TC_Result = 1
        } elseif($testcase.result -eq "Failure") {
            $TC_Result = 2
            # TODO - Get Failure/Stacktrace and add it to the DB (New Table)
        } else{
            $TC_Result = 3
        }

        $query = "insert into TESTCASES (Name,Target_ID,STATUS_ID,RESULT_ID,Test_Suite_ID,Elapsed_Time,Asserts) VALUES ('$TC_NAME','$TC_TARGET_ID','9','$TC_Result','$TestSuite_ID','$TC_Elapsed_Time','$TC_Asserts')"
        $testCase_ID = @(Invoke-MySQLInsert -Query $query -ConnectionString $MyConnectionString)[1]
        $TCcount++
        
        $failure = $testcase.'failure'
        if($failure -ne $null) {
            Pause 3
            write-log -Message "Failure detected in XML for testcase ID: $testCase_ID" -Logfile $logfile
            $Failure_Message = $testcase.'failure'.'message'
            $Stack_Trace = $testcase.'failure'.'stack-trace'
            write-log -Message "Failure: $Failure_Message" -Logfile $logfile
            write-log -Message "Stack Trace: $Stack_Trace" -Logfile $logfile
            $query = "insert into STACKTRACE (MESSAGE,STACKTRACE,TestCase_ID) VALUES ('$Failure_Message','$Stack_Trace','$testCase_ID')"
            Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
        } else {
            write-log -Message "No failure detected in XML." -Logfile $logfile
        }

    }
    
    $TScount++
    write-log -Message "TestSuite count: $TScount" -Logfile $logfile
    write-log -Message "Testcase count: $TCcount" -Logfile $logfile
    write-log -Message "----------End TestSuite----------" -Logfile $logfile
}
# Verify that the number of total test is equal to the number of test cases for this test run.
write-log -Message "Total Tests from XML: $TotalTests" -Logfile $logfile
write-log -Message "Total Testscases from XML: $TCcount" -Logfile $logfile
$query = "select tc.ID,   
				 tc.Name, 
				 tc.Target_ID, 
				 tc.TEST_SUITE_ID, 
				 tc.Elapsed_Time, 
				 tc.Status_ID, 
				 tc.Result_ID, 
				 tc.date_modified, 	
				 tc.Asserts, 									
				 s.Status, 
				 s.HtmlColor, 
				 r.Name as Result, 
				 r.HtmlColor as Result_Color, 
				 t.Target_Name, 
				 t.System_ID, 
				 t.IP_Address, 
				 ts.Name as TestSuiteName 
			 from TESTCASES tc 
			 join x_status s on tc.Status_ID=s.ID 
			 join X_result r on tc.Result_ID=r.ID 
			 join targets t on tc.Target_ID=t.ID 
			 join testsuites ts on tc.TEST_SUITE_ID=ts.ID 
			 where ts.TestRun_ID like $testRun_id and t.System_ID like $TestRun_System_ID"
$TR_Final_Data = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
$TR_Final_Data_Count = $TR_Final_Data.Count

if($TR_Final_Data_Count -eq $TotalTests) {
    write-log -Message "Finished processing TestRun" -Logfile $logfile
} else {
    # Numbers don't match, mark the test as Critical.
    $query = "update testrun set RESULT_ID = 3 where ID like $TestRun_ID"
    Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
    write-log -Message "*********************************************************************" -Logfile $logfile
    write-log -Message "Total Tests $TotalTests number does not equal DB record number: $TR_Final_Data_Count for this test run." -Logfile $logfile
    write-log -Message "*********************************************************************" -Logfile $logfile
    write-log -Message "Finished processing TestRun" -Logfile $logfile
}

#=======================================================================================
# Mark the test as complete
write-log -Message "Marking the TestRun as complete." -Logfile $logfile
$query = "update testrun set STATUS_ID = 9 where ID like '$TestRun_ID'"
Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
#=======================================================================================
#    _  _  _____                    ____       _             
#  _| || ||_   _|__  __ _ _ __ ___ | __ )  ___| | __ _ _   _ 
# |_  ..  _|| |/ _ \/ _` | '_ ` _ \|  _ \ / _ \ |/ _` | | | |
# |_      _|| |  __/ (_| | | | | | | |_) |  __/ | (_| | |_| |
#   |_||_|  |_|\___|\__,_|_| |_| |_|____/ \___|_|\__,_|\__, |
#                                                      |___/ 
#=======================================================================================