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
VALUES (1,60,'c:\\SummitRTS\\Queue_Manager\\Queue_Manager.log');

INSERT INTO TARGET_DIRECTORIES (ID, Directory, STATUS_ID)
VALUES (1,'c:\\OPEN_PROJECTS\\XesterUI\\SAMPLE_DIR',12);

INSERT INTO TESTS (ID,Target_ID,Status_ID,RESULT_ID,Log_file,XML_file)
VALUES (1,1,9,1,'c:\\xesterResults\\1\xester.log','c:\\xesterResults\\1\xester.xml');

