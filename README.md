![Alt text](https://github.com/BelayTechnologies/XesterUI/blob/master/images/XesterUI_belay.png "XesterUI")  

# XesterUI  

## Brief Description:  

User Interface for Powershell unit Testing software. The X is silent.  
This project will consist of an easy to understand User interface, that allows users to submit Unit/Integration tests against Powershell code and Virtual infrastructures, as well as view detailed results and log files from those tests.  
This project will integrate with tools like Pester and Vester.  

## Features  

* Quickly start Vester tests.  
* Sort/Filter drill down to review results at any test level.  
* Easily re-run Tests, with Remediate option.  
* Share your results via a URL. Great for team environments.  

## Components of XesterUI  

* TestRun – A set of specified tests that run on specific Targets.  
* System - A group of Targets, must contain at least one vCenter  
* Target – A single vSphere entity, vCenter, Host, Cluster, vm, etc.  
* Queue Manager – Manages the Queue of tests, assigns them to an appropriate TestRun Manager, also aborts cancelled testruns.  
* TestRun Manager – A process that can execute a TestRun workflow.  
* Workflow – A wrapper script for Vester.  
* Database – Collects all of the meta data about the vSphere systems and TestRun results.  
* User Interface – Set of web pages where you are able to slice and dice the TestRun data, submit TestRuns, Remediate, etc.  

## Dependancies  

* [Vester](https://github.com/WahlNetwork/Vester) 1.2.0 - clearly! This does all the heavy lifting, performing the tests and producing the output xml. No changes needed!  
* [PowerWamp](https://github.com/jpsider/PowerWamp) 3.0.3 - Powershell MySQL plugin to interact with DB.  
* [PowerLumber](https://github.com/jpsider/PowerLumber) 3.0.0 - Powershell logging module.  
* Powershell 5.1 - I've not tested older versions.  
* PowerCLI 6.5.2 - I've not tested older versions.  
* Pester 4.0.8 - Not really sure what version, it ships with Powershell 5.1.  

Currently I have only tested in a windows environment. However the 'amp' could be on a linux machine so long as it had access to the same share for viewing logfiles.  
Review the specific dependancies of these packages for more specific requirements.  

## Installation  

* Install dependancies.  
* Run deploy_XesterUI.ps1 (future)  
* This script will create directories, move files, install modules.  
* Browse to "machine IP"/XesterUI  
* Browse pages to update information in Database.  

## Starting XesterUI  

* Run Start_XesterUI.ps1 (future)  
* This script will start the Queue Manager and TestRun Manager on the local machine.  

## Before you start a test  
Be sure you have entered a correct config.json file in the DB for your systems.  
Remember the current process simply wraps Vester, it does not create a config file on the fly.  

## Starting a Test  
Once your information is updated  
Browse to the Systems page  
Type a 'test name', select whether you want to 'Remediate', and press the 'Start TestRun' button  

## Reviewing Results  
TestRuns are broken down by System, then TestSuites, then TestCases.  
You can filter or sort using the Twitter Bootstrap UI.  
Click around and explore!  

## Support  
The community module is not officially supported and should be used at your own risk.  
Feel free to submit a bug or feature request.  

## Contribution  
I'm not perfect, either is my code! Feel free to contribute to any of our projects!  
Simply Fork the project, Create a feature branch, submit a PR, it will be reviewed before being merged into the master. 

## Blog
Visit my blog for screen captures and more details:
https://invoke-automation.blog
