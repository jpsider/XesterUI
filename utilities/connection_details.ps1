#=======================================================================================
# __  __         _            _   _ ___ 
# \ \/ /___  ___| |_ ___ _ __| | | |_ _|
#  \  // _ \/ __| __/ _ \ '__| | | || | 
#  /  \  __/\__ \ ||  __/ |  | |_| || | 
# /_/\_\___||___/\__\___|_|   \___/|___|
#                                       
#=======================================================================================
# TestRunManager ID
$TestRunManagerID = "1"

# Connection Details for PowerWamp connection String
$MySQLAdminUserName = 'root'
$MySQLAdminPassword = ' '
$MySQLHost = '127.0.0.1'
$MySQLDatabase = 'xesterui'

# Build MySQL connection String
$MyConnectionString = "server=$MySQLHost;port=3306;uid=$MySQLAdminUserName;pwd=$MySQLAdminPassword;database=$MySQLDatabase"

# General Use Information
$workflow_script = "C:\OPEN_PROJECTS\XesterUI\Workflows\Execute_Vester.ps1"
#=======================================================================================
#    _  _  _____                    ____       _             
#  _| || ||_   _|__  __ _ _ __ ___ | __ )  ___| | __ _ _   _ 
# |_  ..  _|| |/ _ \/ _` | '_ ` _ \|  _ \ / _ \ |/ _` | | | |
# |_      _|| |  __/ (_| | | | | | | |_) |  __/ | (_| | |_| |
#   |_||_|  |_|\___|\__,_|_| |_| |_|____/ \___|_|\__,_|\__, |
#                                                      |___/ 
#=======================================================================================