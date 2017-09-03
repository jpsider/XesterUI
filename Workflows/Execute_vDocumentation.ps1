#=======================================================================================
# This Script will Connect to the Database to Gather Script information, 
#   Then execute a vDocumentation with the given information.
#=======================================================================================
# System Variables
set-ExecutionPolicy Bypass -Force

$MYINV = $MyInvocation
$SCRIPTDIR = split-path $MYINV.MyCommand.Path
# import logging, connection details, and mysql cmdlets.
. "$SCRIPTDIR\..\utilities\general-cmdlets.ps1"
. "$SCRIPTDIR\..\utilities\connection_details.ps1"
Import-Module PowerLumber
Import-Module PowerWamp
Get-Module -ListAvailable vmware* | Import-Module

#=======================================================================================
# Gather Args
$TestRun_ID = $args[0]

# Query the DB for the TestRunData
# We need the target ID, System it belongs to, and the vCenter it belongs to
# Get the username/pwd for the vCenter

$Target_ID = 9
$vCenter_ID = 1

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
        where tg.ID like '$vCenter_ID' and tg.Target_Type_ID like '1'"
$vCenterData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
$vc_name = $vCenterData.name
$vc_ip = $vCenterData.IP_Address
$vc_un = $vCenterData.username
$vc_pw = $vCenterData.password


$query = "select Target_Name from targets where ID= $Target_ID and Target_Type_ID=6"
$TargetData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)
$vmhost = $TargetData.Target_Name


$InventoryData = Get-ESXInventory -esxi $vmhost -Hardware -PassThru

$Management_IP = $InventoryData.'Management IP'
$RAC_IP = $InventoryData.'Rac IP'
$RAC_Firmware = $InventoryData.'Rack Firmware'
$Product = $InventoryData.'Product'
$Version = $InventoryData.'Version'
$Build = $InventoryData.'Build'
$Update_Info = $InventoryData.'Update'
$Patch = $InventoryData.'Patch'
$Make = $InventoryData.'Make'
$Model = $InventoryData.'Model'
$Serial_Number = $InventoryData.'S/N'
$BIOS = $InventoryData.'Bios'
$BIOS_Release_Date = $InventoryData.'Bios Release Date'
$CPU_Model = $InventoryData.'CPU Model'
$CPU_Count = $InventoryData.'CPU Count'
$CPU_Core_Total = $InventoryData.'CPU Core Total'
$Speed_MHz = $InventoryData.'Speed (MHz)'
$Memory_GB = $InventoryData.'Memory (GB)'
$Memory_Slots_Count = $InventoryData.'Memory Slots Count'
$Memory_Slots_Used = $InventoryData.'Memroy Slots Used'
$Power_Supplies = $InventoryData.'Power Supplies'
$NIC_Count = $InventoryData.'Nic Count'

$query = "update Hardware_Info set
                Management_IP = '$Management_IP',
                RAC_IP = '$RAC_IP',
                RAC_Firmware = '$RAC_Firmware',
                Product = '$Product',
                Version = '$Version',
                Build = '$Build',
                Update_Info = '$Update_Info',
                Patch = '$Patch',
                Make = '$Make',
                Model = '$Model',
                Serial_Number = '$Serial_Number',
                BIOS = '$BIOS',
                BIOS_Release_Date = '$BIOS_Release_Date',
                CPU_Model = '$CPU_Model',
                CPU_Count = '$CPU_Count',
                CPU_Core_Total = '$CPU_Core_Total',
                Speed_MHz = '$Speed_MHz',
                Memory_GB = '$Memory_GB',
                Memory_Slots_Count = '$Memory_Slots_Count',
                Memory_Slots_Used = '$Memory_Slots_Used',
                Power_Supplies = '$Power_Supplies',
                NIC_Count = '$NIC_Count'
            where Target_ID=$Target_ID"
Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString




# Stuff
$Target_ID = 11
$Attribute_Type_ID=1
$data = Get-Content C:\temp\test.txt
foreach($line in $data){
    $Attribute_Name,$Attribute_Value = $line -split ("=")
    write-host "Attribute Name is $Attribute_Name"
    write-host "Attribute Value is $Attribute_Value"
    write-host "Updating DB"
    $query = "select inv.ID, 
                    inv.Available_Attributes_ID,
                    aa.name
                from inventory_attributes inv
                join AVAILABLE_ATTRIBUTES aa on inv.Available_Attributes_ID=aa.ID
                where aa.name like '$Attribute_Name' and inv.Target_ID=$Target_ID"
    $SingleAttributeData = @(Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString)            
    $InventoryAttrID = $SingleAttributeData.ID
    if($InventoryAttrID -eq $null) {
        write-host "There is no attribute yet, lets add one!"
        $query = "INSERT INTO INVENTORY_ATTRIBUTES (Target_ID,Available_Attributes_ID,Attribute_Value) VALUES 
            ($Target_ID,(Select ID from available_attributes where name like '$Attribute_Name' and Attribute_Type_ID=$Attribute_Type_ID),'$Attribute_Value')"
        Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
    } else{
        write-host "The attribute exists, Lets update it!"
        $query = "update inventory_attributes set Attribute_Value='$Attribute_Value' where ID=$InventoryAttrID"
        Invoke-MySQLQuery -Query $query -ConnectionString $MyConnectionString
    }
}