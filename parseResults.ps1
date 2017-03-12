Import-Module "C:\OPEN_PROJECTS\PowerWamp\powerWamp.psm1"
Import-Module "C:\OPEN_PROJECTS\PowerLumber\PowerLumber.psm1"

#Hardcoded for the moment
$TR_Name = "SampleTest"

$xmldocument = $args[0]
[xml]$xmlData = get-content "$xmlDocument"

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
    $result = 1
}

$MyConnectionString = "server=localhost;port=3306;uid=root;pwd=;database=xesterui"
$query = "insert into TESTRUN (Name,SYSTEM_ID,STATUS_ID,RESULT_ID,Total_Tests,Errors,Failures,NotRun,inconclusive,Ignored,Skipped,Invalid,Elapsed_Time,Log_file,XML_file) VALUES ('$TR_Name',1,9,$Result,$TotalTests,$TotalErrors,$TotalFailures,$TotalNotRun,$TotalInconclusive,$TotalIgnored,$TotalSkipped,$TotalInvalid,'$ElapsedTime','somelog','xmlfile')"
$TestRun_ID = @(Invoke-MySQLInsert -Query $query -ConnectionString $MyConnectionString)[1]

Write-Host "------TEST RUN DATA-------"
Write-Host "TestRun_ID" $TestRun_ID
Write-Host "Total Tests" $TotalTests
Write-Host "Errors" $TotalErrors
Write-Host "Failures" $TotalFailures
Write-Host "NotRun" $TotalNotRun
Write-Host "Inclusive" $TotalInconclusive
Write-Host "Ignored" $TotalIgnored
Write-Host "Skipped" $TotalSkipped
Write-Host "Invalid" $TotalInvalid
Write-Host "Elapsed Time" $ElapsedTime
Write-Host "------ END TEST RUN DATA-------"

$TestSuites = @($xmlData.'test-results'.'test-suite'.results.'test-suite')
$TScount = 0
$TCcount = 0
foreach($testSuite in $TestSuites) {
    write-host "----------START TestSuite----------"
    $TSstring = $testSuite.description
    $TSparts = $TSstring.split(" ",3)
    Write-Host "Target Type:" $TSparts[0]
    Write-host "TestSuite Name:" $TSparts[2]
    write-host "TestSuite Result:" $testSuite.result
    write-host "Elapsed Time:" $testSuite.time
    write-host "Asserts:" $testSuite.asserts
    $TS_NAME = $TSparts[2]
    $TS_Elapsed_Time = $testSuite.time
    $TS_Asserts = $testSuite.asserts

    if($testSuite.result -eq "Success") {
        $TS_Result = 1
    } else {
        $TS_Result = 2
    }

    $query = "insert into TESTSUITES (Name,TestRun_ID,STATUS_ID,RESULT_ID,Elapsed_Time,Asserts) VALUES ('$TS_NAME','$TestRun_ID',9,'$TS_Result','$TS_Elapsed_Time','$TS_Asserts')"
    $TestSuite_ID = @(Invoke-MySQLInsert -Query $query -ConnectionString $MyConnectionString)[1]

    $testcases = $testSuite.results.'test-case'
    foreach($testcase in $testcases){
        #write-host $testcase.description
        $string = $testcase.description
        $parts = $string.split(" ",4)
        write-host "Testcase name:" $parts[3]
        write-host "Testcase result:" $testcase.result
        write-host "Target Type:" $parts[0]
        write-host "Target:" $parts[1]
        $target =$parts[1]
        write-host "Elapsed Time:" $testcase.time
        write-host "Asserts:" $testcase.asserts
        #write-host "Hyphen:" $parts[2]
        if($testcase.result -eq "Success") {
            $TC_Result = 1
        } else {
            $TC_Result = 2
        }

        $query = "select ID from Targets where Target_Name like '$target'"
        $TC_TARGET_ID = (@(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)).ID
        write-host "Target ID:"$TC_TARGET_ID
        $TC_NAME = $parts[3]
        $TC_Elapsed_Time = $testcase.time
        $TC_Asserts = $testcase.asserts
        $query = "insert into TESTCASES (Name,Target_ID,STATUS_ID,RESULT_ID,Test_Suite_ID,Elapsed_Time,Asserts) VALUES ('$TC_NAME','$TC_TARGET_ID','9','$TC_Result','$TestSuite_ID','$TC_Elapsed_Time','$TC_Asserts')"
        Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
        $TCcount++

    }
    
    $TScount++
    write-host $TScount
    write-host $TCcount
    write-host "----------End TestSuite----------"
}
write-host "Done"