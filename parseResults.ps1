$xmldocument = $args[0]
[xml]$xmlData = get-content "$xmlDocument"
$TestSuites = @($xmlData.'test-results'.'test-suite'.results.'test-suite')
$count = 0
foreach($testSuite in $TestSuites) {
    write-host "Test-Suite Name:"
    write-host $testSuite.name
    write-host $testSuite.result
    $testcases = $testSuite.results.'test-case'
    foreach($testcase in $testcases){
        write-host "Test-case Name:"
        write-host $testcase.name
        write-host $testcase.description
        write-host $testcase.result

    }
    
    $count++
    write-host $count
}
write-host "Done"