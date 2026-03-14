<?php

require_once('config.inc.php');
require_once('include/utils/utils.php');
require_once('vtlib/Vtiger/Module.php');
require_once('includes/main/WebUI.php');
include_once 'modules/Accounts/Accounts.php';
global $current_user;

$my_account = new Accounts();
$my_account->column_fields['accountname'] = 'asdasdasdasd';
$my_account->column_fields['email1'] = 's@gmai.com';
$my_account->column_fields['assigned_user_id'] = $current_user->id;
$my_account->save('Accounts');
?>