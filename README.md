![Alt text](https://github.com/BelayTechnologies/XesterUI/blob/master/images/XesterUI.png "XesterUI")  

# XesterUI  

## Brief Description:  

User Interface for Powershell unit Testing software. The X is silent.  
This project will consist of an easy to understand User interface, that allows users to submit Unit tests against Powershell code and Virtual infrastructures, as well as view detailed results and log files from those tests.  
This project will integrate with tools like Pester and Vester.  

## Disclosure  

I initially thought that I would start off using this as a UI to Pester, but after playing with Vester for only 5 minutes, I felt it would be a better launching point for this tool.  
At this time, XesterUI simply wraps the existing Vester project, I have not integrated the code bases at this time.  
This means you will use existing config.json files that you have created and assign them to a system in XesterUI.  
In future releases I hope the two projects will merge together in one way or another to make a more versitile solution.  

## Features  

* Quickly start Vester tests.  
* Sort/Filter drill down to review results at any test level.  
* Easily re-run Tests, with Remediate option.  
* Share your results via a URL. Great for team environments.  

## Components of XesterUI  

* WampServer - Provides the Database and web front end.  
* Queue Manager - Manages TestRuns in the system queue.  
* TestRun Manager - Kicks off Vester with specified config.json.  

## Dependancies  

* [Vester](https://github.com/WahlNetwork/Vester) - clearly! This does all the heavy lifting, performing the tests and producing the output xml. No changes needed!  
* [PowerWamp](https://github.com/jpsider/PowerWamp) - Powershell MySQL plugin to interact with DB.  
* [PowerLumber](https://github.com/jpsider/PowerLumber) - Powershell logging module.  
* Powershell 5.1 - I've not tested older versions.  
* PowerCLI 6.5 - I've not tested older versions.  
* Pester 3.X - Not really sure what version, it ships with Powershell 5.1.  

Currently I have only tested in a windows environment. However the 'amp' could be on a linux machine so long as it had access to the same share for viewing logfiles.  
Review the specific dependancies of these packages for more specific requirements.  

## Installation  

* Install dependancies.  
* Run deploy_XesterUI.ps1  
* This script will create directories, move files, install modules.  
* Browse to "machine IP"/XesterUI  
* Browse pages to update information in Database.  

## Starting XesterUI  

* Run Start_XesterUI.ps1  
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

## Why not use Jenkins for AppVeyor  
You can, but this gives you, the System Administrator more power. This was build by and for System Administrators.  
With easy to understand programming languages so you can customize the system to your needs. I honestly have not spent much time messing with these products to know if they are a better fit for Vester/Pester or VMware Configuration management.  
Plus I like to build things..... and I really like using Powershell and Wamp!   

## Contribution  
I'm not perfect, either is my code! Feel free to contribute to any of my/our projects!  
Simply Fork the project, Create a feature branch, submit a PR, it will be reviewed before being merged into the master. 
