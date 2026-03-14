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
$_SESSION['authenticated_user_id'] = '1';
$val = isRecordExits('sssrr@gmai.com','sssr','sssrr');
$accval = isaccRecordExits('sssrr@gmai.com','asdsdasdasdas');

if (empty($accval)) {
    // CONATCT DETAILS
        $Accounts = CRMEntity::getInstance('Accounts');
        $Accounts->column_fields['assigned_user_id'] = 1;
        $Accounts->column_fields['accountname'] = 'sssrasss';
        $Accounts->column_fields['phne'] = '9898989898';
        $Accounts->column_fields['email1'] = 'sssrr@gmai.com';
        $Accounts->save('Accounts');
        $AccountsModuleId = $Accounts->id;
    }else{
        $recordModel = Accounts_Record_Model::getInstanceById($accval);
        $recordModel->set('mode', 'edit');
        $recordModel->set("accountname",'asdsdasdasdas');
        $recordModel->set("phone", '987989898');
        $recordModel->set("email1", 'p@g.com');
        $recordModel->save();
    }

if (empty($val)) {
// CONATCT DETAILS
    $Contacts = CRMEntity::getInstance('Contacts');
    $Contacts->column_fields['assigned_user_id'] = 1;
    $Contacts->column_fields['firstname'] = 'sssrasss';
    $Contacts->column_fields['lastname'] = 'sddsd';
    $Contacts->column_fields['email'] = 'sssrr@gmai.com';
    $Contacts->column_fields['account_id'] = $AccountsModuleId;
    $Contacts->save('Contacts');
    $ContactsModuleId = $Contacts->id;
}else{
    $recordModel = Contacts_Record_Model::getInstanceById($val);
    $recordModel->set('mode', 'edit');
    $recordModel->set("firstname",'asdsdasdasdas');
    $recordModel->set("lastname", 'cdscdccd');
    $recordModel->set("email", 'p@g.com');
    $recordModel->save();
}

// Setting up HelpDesk instance
$HelpDesk = CRMEntity::getInstance('HelpDesk');
$HelpDesk->column_fields['assigned_user_id'] = $current_user->id;
$HelpDesk->column_fields['ticket_title'] = 'sssr';
$HelpDesk->column_fields['ticketpriorities'] = 'Low';
$HelpDesk->column_fields['ticketstatus'] = 'Open';
$HelpDesk->column_fields['contact_id'] = $ContactsModuleId;
$HelpDesk->column_fields['parent_id'] = $AccountsModuleId;
$HelpDesk->save('HelpDesk');
$HelpDeskModuleId = $HelpDesk->id;

createRelationBetweenAccountAndContact($AccountsModuleId,$ContactsModuleId);
function createRelationBetweenAccountAndContact($AccountsModuleId,$ContactsModuleId )
{
    global $adb;
    $relation = new Relation();
    $relation->addRelation('Accounts', $AccountsModuleId,'Contacts', $ContactsModuleId);
}

// Create relationship between HelpDesk and Contact
createRelationBetweenHelpDeskAndContact($ContactsModuleId,$HelpDeskModuleId);
function createRelationBetweenHelpDeskAndContact($ContactsModuleId, $HelpDeskModuleId )
{
    global $adb;
    $relation = new Relation();
    $relation->addRelation('Contacts', $ContactsModuleId,'HelpDesk', $HelpDeskModuleId);
}
function isRecordExits($email,$firstname,$lastname) {
    global $adb;
    $sql = 'select contactid from vtiger_contactdetails 
                INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid
    where email = ? and firstname= ? and lastname= ? and vtiger_crmentity.deleted = 0';
    $sqlResult = $adb->pquery($sql, array($email, $firstname, $lastname));
    $num_rows = $adb->num_rows($sqlResult);
    if ($num_rows > 0) {
        $dataRow = $adb->fetchByAssoc($sqlResult, 0);
        return $dataRow['contactid'];
    } else {
        return '';
    }
}

function isaccRecordExits($email,$firstname) {
    global $adb;
    $sql = 'select accountid from vtiger_account
                INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_account.accounttid
    where email1 = ? and accountname= ?  and vtiger_crmentity.deleted = 0';
    $sqlResult = $adb->pquery($sql, array($email, $firstname));
    $num_rows = $adb->num_rows($sqlResult);
    if ($num_rows > 0) {
        $dataRow = $adb->fetchByAssoc($sqlResult, 0);
        return $dataRow['accountid'];
    } else {
        return '';
    }
}

