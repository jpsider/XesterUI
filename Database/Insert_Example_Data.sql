use XesterUI;

INSERT INTO X_STATUS (ID, Status, HtmlColor, HTML_Description)
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

INSERT INTO X_RESULT (ID, Name, HtmlColor, HTML_Description)
VALUES (1,'PASS','#006633','Green'),
(2,'FAIL','#CC0000','Red'),
(3,'CRITICAL','#FFFF00','Yellow'),
(4,'AGENT_ERROR','#9933FF','Purple'),
(5,'ABORTED','#FF6600','Orange'),
(6,'UNKNOWN','#666666','Grey');

INSERT INTO QUEUE_MANAGER (Status_ID, Wait, Log_File)
VALUES (1,60,'c:\\XesterUI\\Queue_Manager\\Queue_Manager.log');

INSERT INTO TESTRUN_MANAGER (Status_ID, Wait, Max_Concurrent, Log_File)
VALUES (1,60,4,'c:\\XesterUI\\TESTRUN_Manager\\TESTRUN_Manager.log');

INSERT INTO PASSWORDS (ID, Username, Password)
VALUES (1,'administrator@corp.local','VMware1!'),(2,'administrator','BelayTech2015'),(3,'root','BelayTech2015');

INSERT INTO Target_Types (ID, Name)
VALUES (1,'vcenter'),(2,'DataCenter'),(3,'Cluster'),(4,'VM'),(5,'vds'),(6,'Host'),(7,'DSCluster'),(8,'Network');

INSERT INTO SYSTEMS (ID,SYSTEM_Name,STATUS_ID)
VALUES (1,'HomeLab',11);

INSERT INTO TARGETS (ID,Target_Name,Target_Type_ID,IP_Address,STATUS_ID,Password_ID,System_ID)
VALUES (1,'192.168.2.200',1,'192.168.2.200',11,1,1),(2,'DC01',2,'na',11,1,1),(3,'Cluster01',3,'na',11,1,1),(4,'Win_7_test_VM',4,'na',11,2,1),(5,'Win_10_test_VM',4,'na',11,2,1),
(6,'CentOS_6_test_vm',4,'na',11,3,1),(7,'Android_4_test_vm',4,'na',11,1,1),(8,'Embedded-vCenter-Server-Appliance',4,'na',11,3,1),(9,'192.168.2.202',6,'192.168.2.202',11,1,1);

-- Review below this line

INSERT INTO PASSWORDS (ID, Username, Password)
VALUES 
(4,'team9','VMware1!'),
(5,'administrator@vsphere.local','VMware1!');

INSERT INTO SYSTEMS (ID,SYSTEM_Name,STATUS_ID)
VALUES (2,'Hackathon',11);

INSERT INTO TARGETS (Target_Name,Target_Type_ID,IP_Address,STATUS_ID,Password_ID,System_ID,Config_File)
VALUES ('192.168.9.10',1,'192.168.9.10',11,4,2,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config.json');



INSERT INTO SYSTEMS (ID,SYSTEM_Name,STATUS_ID)
VALUES (3,'Hackathon_all',11);

INSERT INTO TARGETS (Target_Name,Target_Type_ID,IP_Address,STATUS_ID,Password_ID,System_ID,Config_File)
VALUES 
('192.168.0.10',1,'192.168.0.10',11,5,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_Primary.json'),
('192.168.1.10',1,'192.168.1.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_1.json'),
('192.168.2.10',1,'192.168.2.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_2.json'),
('192.168.3.10',1,'192.168.3.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_3.json'),
('192.168.4.10',1,'192.168.4.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_4.json'),
('192.168.5.10',1,'192.168.5.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_5.json'),
('192.168.6.10',1,'192.168.6.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_6.json'),
('192.168.7.10',1,'192.168.7.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_7.json'),
('192.168.8.10',1,'192.168.8.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_8.json'),
('192.168.9.10',1,'192.168.9.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_9.json'),
('192.168.10.10',1,'192.168.10.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_10.json'),
('192.168.11.10',1,'192.168.11.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_11.json'),
('192.168.12.10',1,'192.168.12.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_12.json'),
('192.168.13.10',1,'192.168.13.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_13.json'),
('192.168.14.10',1,'192.168.14.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_14.json'),
('192.168.15.10',1,'192.168.15.10',11,4,3,'C:\\Program Files\\WindowsPowerShell\\Modules\\Vester\\1.2.0\\Configs\\Hackathon_Config_team_15.json');


INSERT INTO ATTRIBUTE_TYPE (ID,Name) VALUES
(1,'Hardware'),
(2,'Configuration'),
(3,'VirtualSwitch'),
(4,'ISCI'),
(5,'FibreChannel'),
(6,'DataStore'),
(7,'IODevice'),
(8,'PhysicalAdapter'),
(9,'VMKernelAdapter');

INSERT INTO AVAILABLE_ATTRIBUTES (ID,Name,Attribute_Type_ID) VALUES
(1,'Management IP',1),
(2,'RAC IP',1),
(3,'RAC Firmware',1),
(4,'Product',1),
(5,'Version',1),
(6,'Build',1),
(7,'Update',1),
(8,'Patch',1),
(9,'Make',1),
(10,'Model',1),
(11,'S/N',1),
(12,'BIOS',1),
(13,'BIOS Release Date',1),
(14,'CPU Model',1),
(15,'CPU Count',1),
(16,'CPU Core Total',1),
(17,'Speed (MHz)',1),
(18,'Memory (GB)',1),
(19,'Memory Slots Count',1),
(20,'Memory Slots Used',1),
(21,'Power Supplies',1),
(22,'Nic Count',1),
(23,'Make',2),
(24,'Model',2),
(25,'CPU Model',2),
(26,'Hyper-Threading',2),
(27,'Max EVC Mode',2),
(28,'Product',2),
(29,'Version',2),
(30,'Build',2),
(31,'Update',2),
(32,'Patch',2),
(33,'License Version',2),
(34,'License Key',2),
(35,'Connection State',2),
(36,'Standalone',2),
(37,'Cluster',2),
(38,'Virtual Datacenter',2),
(39,'vCenter',2),
(40,'Software/Patch Last Installed',2),
(41,'Software/Patch Name(s)',2),
(42,'Service',2),
(43,'Service Running',2),
(44,'Startup Policy',2),
(45,'NTP Client Enabled',2),
(46,'NTP Server',2),
(47,'Syslog Server',2),
(48,'Syslog Client Enabled',2),
(49,'Slot Description',7),
(50,'VMKernel Name',7),
(51,'Device Name',7),
(52,'Vendor Name',7),
(53,'Device Class',7),
(54,'PCI Address',7),
(55,'VID',7),
(56,'DID',7),
(57,'SVID',7),
(58,'SSID',7),
(59,'Driver',7),
(60,'Driver Version',7),
(61,'Firmware Version',7),
(62,'VIB Name',7),
(63,'VIB Version',7),
(64,'Name',8),
(65,'Slot Description',8),
(66,'Device',8),
(67,'Duplex',8),
(68,'Link',8),
(69,'MAC',8),
(70,'MTU',8),
(71,'Speed',8),
(72,'vSwitch',8),
(73,'vSwitch MTU',8),
(74,'Discovery Protocol',8),
(75,'Device ID',8),
(76,'Device IP',8),
(77,'Port',8),
(78,'Name',9),
(79,'MAC',9),
(80,'MTU',9),
(81,'IP',9),
(82,'Subnet Mask',9),
(83,'TCP/IP Stack',9),
(84,'Default Gateway',9),
(85,'DNS',9),
(86,'PortGroup Name',9),
(87,'VLAN ID',9),
(88,'Enabled Services',9),
(89,'vSwitch',9),
(90,'vSwitch MTU',9),
(91,'Active adapters',9),
(92,'Standby adapters',9),
(93,'Unused adapters',9),
(94,'Type',3),
(95,'Version',3),
(96,'Name',3),
(97,'Uplink/ConnectedAdapters',3),
(98,'PortGroup',3),
(99,'VLAN ID',3),
(100,'Active adapters',3),
(101,'Standby adapters',3),
(102,'Unused adapters',3),
(103,'Security Promiscuous/MacChanges/ForgedTransmits',3),
(104,'Device',4),
(105,'ISCSI Name',4),
(106,'Model',4),
(107,'Send Targets',4),
(108,'Static Targets',4),
(109,'Port Group',4),
(110,'VMKernel Adapter',4),
(111,'Port Binding',4),
(112,'Path Status',4),
(113,'Physical Network Adapter',4),
(114,'Device',5),
(115,'Model',5),
(116,'Node WWN',5),
(117,'Port WWN',5),
(118,'Driver',5),
(119,'Speed (GB)',5),
(120,'Status',5),
(121,'DataStore Name',6),
(122,'Device Name',6),
(123,'Canonical Name',6),
(124,'LUN',6),
(125,'Type',6),
(126,'Datastore Cluster',6),
(127,'Capacity (GB)',6),
(128,'Provisioned Space (GB)',6),
(129,'Free Space (GB)',6),
(130,'Transport',6),
(131,'Mount Point',6),
(132,'Multipath Policy',6),
(133,'File System Version',6);

INSERT INTO INVENTORY_ATTRIBUTES (ID,Target_ID,Available_Attributes_ID,Attribute_Value) VALUES
(1,11,12,'2.7.0'),
(2,11,13,'10/30/2010'),
(3,9,12,'2.7.0'),
(4,9,13,'10/30/2010');



