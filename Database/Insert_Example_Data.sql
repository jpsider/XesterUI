use xesterui;

INSERT INTO STATUS (ID, Status, HtmlColor, HTML_Description)
VALUES (1,'Down','#CC0000','Red'),
(2,'Up','#006633','Green'),
(3,'Starting Up','#FFFF00','Yellow'),
(4,'Shutting Down','#FF6600','Orange'),
(5,'Submitted','#666666','Grey'),
(6,'Queued','#FFFFFF','White'),
(7,'Assigned','#6699FF','SkyBlue'),
(8,'Running','#0066FF','Blue'),
(9,'Complete','#00CC66','LightGreen'),
(10,'Cancelled','#333333','Charcoal'),
(11,'Enabled','#006633','Green'),
(12,'Disabled','#CC0000','Red');

INSERT INTO RESULTS (ID, Name, HtmlColor, HTML_Description)
VALUES (1,'PASS','#006633','Green'),
(2,'FAIL','#CC0000','Red'),
(3,'CRITICAL','#FFFF00','Yellow'),
(4,'AGENT_ERROR','#9933FF','Purple'),
(5,'ABORTED','#FF6600','Orange'),
(6,'UNKNOWN','#666666','Grey');

INSERT INTO QUEUE_MANAGER (Status_ID, Wait, Log_File)
VALUES (1,60,'c:\\XesterUI\\Queue_Manager\\Queue_Manager.log');

INSERT INTO TESTRUN_MANAGER (ID,Status_ID, Wait, Max_Concurrent, Log_File)
VALUES (1,1,60,4,'c:\\XesterUI\\TESTRUN_Manager\\TESTRUN_Manager.log');
VALUES (9999,1,60,4,'nolog');

INSERT INTO PASSWORDS (ID, Username, Password)
VALUES (1,'administrator@corp.local','VMware1!'),
(2,'administrator@vsphere.local','VMware1!'),
(3,'administrator','VMware1!');

INSERT INTO Target_Types (ID, Name)
VALUES 
(1,'vcenter'),
(2,'DataCenter'),
(3,'Cluster'),
(4,'VM'),
(5,'vds'),
(6,'Host'),
(7,'DSCluster'),
(8,'Network');

INSERT INTO SYSTEMS (ID,SYSTEM_Name,STATUS_ID)
VALUES 
(1,'HomeLab',11),
(2,'MultiLab',11),
(3,'FancyLab',11),
(4,'SingleLab',11);

INSERT INTO TARGETS (ID,Target_Name,Target_Type_ID,IP_Address,STATUS_ID,Password_ID,System_ID,Config_File)
VALUES 
(1,'192.168.2.220',1,'192.168.2.220',11,1,1,'C:\\XesterUI\\Configs\\Fancy_Config.json'),
(1,'192.168.2.220',1,'192.168.2.220',11,1,2,'C:\\XesterUI\\Configs\\Fancy_Config.json'),
(1,'192.168.2.220',1,'192.168.2.220',11,1,3,'C:\\XesterUI\\Configs\\Fancy_Config.json'),
(1,'192.168.2.220',1,'192.168.2.220',11,1,4,'C:\\XesterUI\\Configs\\Fancy_Config.json'),




