<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);  

require_once 'vendor/autoload.php';
include_once 'config.php';
include_once 'include/Webservices/Relation.php';
include_once 'vtlib/Vtiger/Module.php';
include_once 'includes/main/WebUI.php';

global $adb;
global $current_user;
$current_user = CRMEntity::getInstance('Users');
$userid = Users::getActiveAdminId();
$current_user->retrieveCurrentUserInfoFromFile($userid);

// Initialize record counter
$totalRecordsImported = 0;

// Make sure file upload is properly handled
if (isset($_POST['upload']) && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];

    // Check if file was uploaded without errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileTmpName = $file['tmp_name'];
        $fileName = $file['name'];

        // Ensure the file is a CSV
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if ($fileExtension !== 'csv') {
            echo "Please upload a CSV file.";
            exit;
        }

        // Open the file in read mode
        if (($handle = fopen($fileTmpName, 'r')) !== FALSE) {
            fgetcsv($handle, 1000, ',');
            // Loop through each line of the CSV file
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {

                // Ensure there is valid data in the row
                    if(!empty($data)){
                    // Process the row
                    $boid = $data[0];
                    $sapcode = $data[1];
                    $custcode = $data[2];
                    $depocode = $data[3];
                    $depotname = $data[4];
                    $zone = $data[5];
                    $servicecord = $data[6];
                    //orgisation
                    $orgname = $data[7];
                    //contacts
                    $custname = $data[8];
                    $address = $data[9];
                    $city = $data[10];
                    $pincode = $data[11];
                    $state = $data[12];

                    $typeofmc = $data[13];
                    $model = $data[14];
                    $mserialno = $data[15];
                    $doin = DateTime::createFromFormat('j-F-y', $data[16]);  
                    $doi = $doin->format('Y-m-d');

                    //dealer
                    $dcategory = $data[17];
                    $deaname = $data[18];
                    $deamob = $data[19];

                    //engineer
                    $engname = $data[20];
                    $engcode = $data[21];
                    $engmob = $data[22];

                    $serlocation = $data[23];
                    $tat = $data[24];
                    $gdata = $data[25];
                    $conn = $data[26];
                    $devdetails = $data[27];
                    $gyrodetails = $data[28];
                    $gyromodel = $data[29];
                    $gyroserialno = $data[30];
                    $upsdetails = $data[31];
                    $upsserialno = $data[32];
                    $warmonth = $data[33];
                    $sdate = DateTime::createFromFormat('j-F-y', $data[34]);  
                    $wsddate = $sdate->format('Y-m-d');
                    $edate = DateTime::createFromFormat('j-F-y', $data[35]);  
                    $weddate = $edate->format('Y-m-d');
                    $staus = $data[36];
                    $remarks = $data[37];

                    // Check if account and contact exist
                    $accval = isaccRecordExits($orgname);
                    $val = isRecordExits($custname,$address,$city,$state,$pincode);
                    $engval = isengRecordExits($engname,$engcode);
                    $deaval = isdelRecordExits($deaname,$deamob);
                    $servval = isservRecordExits($servicecord);

                    if (empty($servval)) {
                        // Create a new Account
                        $ServiceCordinator = CRMEntity::getInstance('ServiceCordinator');
                        $ServiceCordinator->column_fields['assigned_user_id'] = 1;
                        $ServiceCordinator->column_fields['sm_code'] = $servicecord.'00XX';
                        $ServiceCordinator->column_fields['lastname'] = $servicecord;
                        $ServiceCordinator->save('ServiceCordinator');
                        $ServiceCordinatorModuleId = $ServiceCordinator->id;
                    } else {
                        $ServiceCordinatorId = $servval;
                    }

                       // Check and create or update Engineer
                    if (empty($engval)) {
                        // Create a new Account
                        $Engineer = CRMEntity::getInstance('Engineer');
                        $Engineer->column_fields['assigned_user_id'] = 1;
                        $Engineer->column_fields['engineer_code'] = $engcode;
                        $Engineer->column_fields['engineer_name'] = $engname;
                        $Engineer->column_fields['servicecordintor_id'] = $ServiceCordinatorId;
                        $Engineer->column_fields['mobile_no'] = $engmob;
                        $Engineer->save('Engineer');
                        $EngineerModuleId = $Engineer->id;
                    } else {
                        $EngineerModuleId = $engval;
                    }

                         // Check and create or update Dealer
                    if (empty($deaval)) {
                        // Create a new Dealer
                        $Vendors = CRMEntity::getInstance('Vendors');
                        $Vendors->column_fields['assigned_user_id'] = 1;
                        $Vendors->column_fields['vendorname'] = $deaname;
                        $Vendors->column_fields['glacct'] = $dcategory;
                        $Vendors->column_fields['phone'] = $deamob;
                        $Vendors->save('Vendors');
                        $DealerModuleId = $Vendors->id;
                        } else {
                        $DealerModuleId = $deaval;
                        }

                    // Check and create or update Account
                    if (empty($accval)) {
                        // Create a new Account
                        $Accounts = CRMEntity::getInstance('Accounts');
                        $Accounts->column_fields['assigned_user_id'] = 1;
                        $Accounts->column_fields['accountname'] = $orgname;
                        $Accounts->save('Accounts');
                        $AccountsModuleId = $Accounts->id;
                    } else {
                        // Update existing Account
                        // $recordModel = Accounts_Record_Model::getInstanceById($accval);
                        // $recordModel->set('mode', 'edit');
                        // $recordModel->set("accountname", $orgname);
                        // $recordModel->save();
                        $AccountsModuleId = $accval;
                    }

                    // Check and create or update Contact
                    if (empty($val)) {
                        // Create a new Contact
                        $Contacts = CRMEntity::getInstance('Contacts');
                        $Contacts->column_fields['assigned_user_id'] = 1;
                        $Contacts->column_fields['lastname'] = $custname;
                        $Contacts->column_fields['mailingstreet'] = $address;
                        $Contacts->column_fields['mailingcity'] = $city;
                        $Contacts->column_fields['mailingstate'] = $state;
                        $Contacts->column_fields['mailingzip'] = $pincode;
                        $Contacts->column_fields['account_id'] = $AccountsModuleId;
                        $Contacts->save('Contacts');
                        $ContactsModuleId = $Contacts->id;
                    } else {
                        // Update existing Contact
                        // $recordModel = Contacts_Record_Model::getInstanceById($val);
                        // $recordModel->set('mode', 'edit');
                        // $recordModel->set("lastname", $lastname);
                        // $recordModel->set("mailingstreet", $address);
                        // $recordModel->set("mailingcity", $city);
                        // $recordModel->set("mailingstate", $state);
                        // $recordModel->set("mailingzip", $pin);
                        //$recordModel->save();
                        $ContactsModuleId = $val;
                    }

                   

                    // Create or update HelpDesk record
                    $HelpDesk = CRMEntity::getInstance('HelpDesk');
                    $HelpDesk->column_fields['assigned_user_id'] = $current_user->id;
                    $HelpDesk->column_fields['boid'] = $boid;
                    $HelpDesk->column_fields['sapcode'] = $sapcode;
                    $HelpDesk->column_fields['custcode'] = $custcode;
                    $HelpDesk->column_fields['depocode'] = $depocode;
                    $HelpDesk->column_fields['depotname'] = $depotname;
                    $HelpDesk->column_fields['zone'] = $zone;
                  
                    $HelpDesk->column_fields['serviceengineer'] = $servicecord;
                    $HelpDesk->column_fields['parent_id'] = $AccountsModuleId;
                    $HelpDesk->column_fields['contact_id'] = $ContactsModuleId;
                    $HelpDesk->column_fields['contact_mobileno'] = $deamob;
                    $HelpDesk->column_fields['eng_mobileno'] = $engmob;
                    $HelpDesk->column_fields['status'] = $status;
                    $HelpDesk->column_fields['typeofmc'] = $typeofmc;
                    $HelpDesk->column_fields['model'] = $model;
                    $HelpDesk->column_fields['serialno'] = $mserialno;
                    $HelpDesk->column_fields['doi'] = $doi;
                    $HelpDesk->column_fields['vendor_id'] = $DealerModuleId;
                    $HelpDesk->column_fields['engineer_name'] = $EngineerModuleId;
                    $HelpDesk->column_fields['service_location'] = $serlocation;
                    $HelpDesk->column_fields['tat'] = $tat;
                    $HelpDesk->column_fields['gdata'] = $gdata;
                    $HelpDesk->column_fields['connectivity'] = $conn;

                    $HelpDesk->column_fields['address'] = $address;     
                    $HelpDesk->column_fields['city'] = $city;
                    $HelpDesk->column_fields['state'] = $state;
                    $HelpDesk->column_fields['pincode'] = $pincode;
                    $HelpDesk->column_fields['device_details'] = $devdetails;
                    $HelpDesk->column_fields['gyro_type'] = $gyrodetails;
                    $HelpDesk->column_fields['gyro_model'] = $gyromodel;
                    $HelpDesk->column_fields['gyro_serialno'] = $gyroserialno;
                    $HelpDesk->column_fields['ups_details'] = $upsdetails;
                    $HelpDesk->column_fields['ups_serialno'] = $upsserialno;
                    $HelpDesk->column_fields['warranty_month'] = $warmonth;
                    $HelpDesk->column_fields['warranty_start_date'] = $wsddate;
                    $HelpDesk->column_fields['warranty_end_date'] = $weddate;
                    $HelpDesk->column_fields['status'] = $status;
                    $HelpDesk->column_fields['description'] = $remarks;
                   
                    $HelpDesk->save('HelpDesk');
                    $HelpDeskModuleId = $HelpDesk->id;

                    // Optional Relations
                   // createRelationBetweenAccountAndContact($AccountsModuleId, $ContactsModuleId);
                  //  createRelationBetweenHelpDeskAndContact($ContactsModuleId, $HelpDeskModuleId);

                    // Increment the total records imported
                    $totalRecordsImported++;
                }
            }

            // Close the file after processing
            fclose($handle);

            // Success message with total records imported
            echo "File imported successfully! Total records imported: " . $totalRecordsImported;
        } else {
            echo "Failed to open the file.";
        }    
    } else {
        echo "File upload failed.";
    }
}

// Functions to create relations
function createRelationBetweenAccountAndContact($AccountsModuleId, $ContactsModuleId)
{
    global $adb;
    $relation = new Relation();
    $relation->addRelation('Accounts', $AccountsModuleId, 'Contacts', $ContactsModuleId);
}

function createRelationBetweenHelpDeskAndContact($ContactsModuleId, $HelpDeskModuleId)
{
    global $adb;
    $relation = new Relation();
    $relation->addRelation('Contacts', $ContactsModuleId, 'HelpDesk', $HelpDeskModuleId);
}

function createRelationBetweenHelpDeskAndVendor($DealerModuleId, $HelpDeskModuleId)
{
    global $adb;
    $relation = new Relation();
    $relation->addRelation('Vendors', $DealerModuleId, 'HelpDesk', $HelpDeskModuleId);
}

function createRelationBetweenHelpDeskAndEngineer($EngineerModuleId, $HelpDeskModuleId)
{
    global $adb;
    $relation = new Relation();
    $relation->addRelation('Engineer', $EngineerModuleId, 'HelpDesk', $HelpDeskModuleId);
}

function createRelationBetweenServiceCordinatorAndEngineer($ServiceCordinatorId, $EngineerModuleId)
{
    global $adb;
    $relation = new Relation();
    $relation->addRelation('ServiceCordinator', $ServiceCordinatorId, 'Engineer', $EngineerModuleId);
}

function createRelationBetweenHelpDeskAndServiceCordinator($ServiceCordinatorId, $HelpDeskModuleId)
{
    global $adb;
    $relation = new Relation();
    $relation->addRelation('ServiceCordinator', $ServiceCordinatorId, 'HelpDesk', $HelpDeskModuleId);
}

function isRecordExits($custname,$address,$city,$state,$pincode)
{
    
    global $adb;
    $sql = 'SELECT vtiger_contactdetails.contactid FROM vtiger_contactdetails
    INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid
    INNER JOIN vtiger_contactaddress ON vtiger_contactaddress.contactaddressid = vtiger_contactdetails.contactid
    WHERE vtiger_contactdetails.lastname = ? 
    AND vtiger_contactaddress.mailingstreet = ? 
    AND vtiger_contactaddress.mailingcity = ? 
    AND vtiger_contactaddress.mailingstate = ? 
    AND vtiger_contactaddress.mailingzip = ? AND vtiger_crmentity.deleted = 0';
    $sqlResult = $adb->pquery($sql, array($custname,$address,$city,$state,$pincode));
    $num_rows = $adb->num_rows($sqlResult);
    if ($num_rows > 0) {
        $dataRow = $adb->fetchByAssoc($sqlResult, 0);
        return $dataRow['contactid'];
    } else {
        return '';
    }
}

function isengRecordExits($engname,$engcode)
{
    global $adb;
    $sql = 'SELECT engineerid FROM vtiger_engineer
            INNER JOIN vtiger_crmentity
            ON vtiger_crmentity.crmid = vtiger_engineer.engineerid
            WHERE engineer_name = ? AND engineer_code = ? AND vtiger_crmentity.deleted = 0';
    $sqlResult = $adb->pquery($sql, array($engname, $engcode));
    $num_rows = $adb->num_rows($sqlResult);
    if ($num_rows > 0) {
        $dataRow = $adb->fetchByAssoc($sqlResult, 0);
        return $dataRow['engineerid'];
    } else {
        return '';
    }
}

function isdelRecordExits($deaname,$deamob)
{
    global $adb;
    $sql = 'SELECT vendorid FROM vtiger_vendor
            INNER JOIN vtiger_crmentity
            ON vtiger_crmentity.crmid =  vtiger_vendor.vendorid
            WHERE vendorname = ? AND phone = ? AND vtiger_crmentity.deleted = 0';
    $sqlResult = $adb->pquery($sql, array($deaname, $deamob));
    $num_rows = $adb->num_rows($sqlResult);
    if ($num_rows > 0) {
        $dataRow = $adb->fetchByAssoc($sqlResult, 0);
        return $dataRow['vendorid'];
    } else {
        return '';
    }
}

function isservRecordExits($servicecord)
{
    global $adb;
    $sql = 'SELECT servicecordinatorid FROM vtiger_servicecordinator
            INNER JOIN vtiger_crmentity
            ON vtiger_crmentity.crmid =  vtiger_servicecordinator.servicecordinatorid
            WHERE lastname = ? AND vtiger_crmentity.deleted = 0';
    $sqlResult = $adb->pquery($sql, array($servicecord));
    $num_rows = $adb->num_rows($sqlResult);
    if ($num_rows > 0) {
        $dataRow = $adb->fetchByAssoc($sqlResult, 0);
        return $dataRow['servicecordintorid'];
    } else {
        return '';
    }
}

function isaccRecordExits($orgname)
{
    global $adb;
    $sql = 'SELECT accountid FROM vtiger_account
            INNER JOIN vtiger_crmentity
            ON vtiger_crmentity.crmid = vtiger_account.accountid
            WHERE accountname= ? AND vtiger_crmentity.deleted = 0';
    $sqlResult = $adb->pquery($sql, array($orgname));
    $num_rows = $adb->num_rows($sqlResult);
    if ($num_rows > 0) {
        $dataRow = $adb->fetchByAssoc($sqlResult, 0);
        return $dataRow['accountid'];
    } else {
        return '';
    }
}
?>
