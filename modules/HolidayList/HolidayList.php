<?php

include_once 'modules/Vtiger/CRMEntity.php';

class HolidayList extends Vtiger_CRMEntity
{
	var $table_name = 'vtiger_holidaylist';
	var $table_index = 'holidaylistid';

	var $customFieldTable = array('vtiger_holidaylistcf', 'holidaylistid');

	var $tab_name = array('vtiger_crmentity', 'vtiger_holidaylist', 'vtiger_holidaylistcf');

	var $tab_name_index = array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_holidaylist' => 'holidaylistid',
		'vtiger_holidaylistcf' => 'holidaylistid'
	);

	var $list_fields = array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Assigned To' => array('crmentity', 'smownerid')
	);
	var $list_fields_name = array(
		/* Format: Field Label => fieldname */
		'Assigned To' => 'assigned_user_id',
	);

	// Make the field link to detail view
	var $list_link_field = 'holidayname';

	// For Popup listview and UI type support
	var $search_fields = array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Assigned To' => array('vtiger_crmentity', 'assigned_user_id'),
	);
	var $search_fields_name = array(
		/* Format: Field Label => fieldname */
		'Assigned To' => 'assigned_user_id',
	);

	// For Popup window record selection
	var $popup_fields = array('holidayname');

	// For Alphabetical search
	var $def_basicsearch_col = 'holidayname';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'holidayname';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = array('holidayname', 'assigned_user_id');

	var $default_order_by = 'holidayname';
	var $default_sort_order = 'ASC';

	//var $groupTable = Array('vtiger_ticketgrouprelation','ticketid');

	/**	Constructor which will set the column_fields in this object
	 */
	function __construct()
	{
		$this->log = Logger::getLogger('HolidayList');
		$this->log->debug("Entering HolidayList() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('HolidayList');
		$this->log->debug("Exiting HolidayList method ...");
	}
	function HolidayList()
	{
		self::__construct();
	}

	function save_module($module)
	{
		global $adb;

		if (!empty($this->column_fields['holidaydate'])) {
			$day = date('l', strtotime($this->column_fields['holidaydate']));
			$adb->pquery("UPDATE vtiger_holidaylist SET dayname = ? WHERE holidaylistid = ?", [$day, $this->id]);
		}
	}


	/**
	 *      This function is used to add the vtiger_attachments. This will call the function uploadAndSaveFile which will upload the attachment into the server and save that attachment information in the database.
	 *      @param int $id  - entity id to which the vtiger_files to be uploaded
	 *      @param string $module  - the current module name
	 */


	/** Function to form the query to get the list of activities
	 *  @param  int $id - ticket id
	 *	@return array - return an array which will be returned from the function GetRelatedList
	 **/
	function get_activities($id, $cur_tab_id, $rel_tab_id, $actions = false)
	{
		global $log, $singlepane_view, $currentModule, $current_user;
		$log->debug("Entering get_activities(" . $id . ") method ...");
		$this_module = $currentModule;

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/Activity.php");
		$other = new Activity();
		vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if ($singlepane_view == 'true')
			$returnset = '&return_module=' . $this_module . '&return_action=DetailView&return_id=' . $id;
		else
			$returnset = '&return_module=' . $this_module . '&return_action=CallRelatedList&return_id=' . $id;

		$button = '';

		$button .= '<input type="hidden" name="activity_mode">';

		if ($actions) {
			if (is_string($actions)) $actions = explode(',', strtoupper($actions));
			if (in_array('ADD', $actions) && isPermitted($related_module, 1, '') == 'yes') {
				if (getFieldVisibilityPermission('Calendar', $current_user->id, 'parent_id', 'readwrite') == '0') {
					$button .= "<input holidayname='" . getTranslatedString('LBL_NEW') . " " . getTranslatedString('LBL_TODO', $related_module) . "' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Task\";' type='submit' name='button'" .
						" value='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString('LBL_TODO', $related_module) . "'>&nbsp;";
				}
				if (getFieldVisibilityPermission('Events', $current_user->id, 'parent_id', 'readwrite') == '0') {
					$button .= "<input holidayname='" . getTranslatedString('LBL_NEW') . " " . getTranslatedString('LBL_TODO', $related_module) . "' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Events\";' type='submit' name='button'" .
						" value='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString('LBL_EVENT', $related_module) . "'>";
				}
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name' =>
		'vtiger_users.first_name', 'last_name' => 'vtiger_users.last_name'), 'Users');
		$query = "SELECT case when (vtiger_users.user_name not like '') then $userNameSql else vtiger_groups.groupname end as user_name," .
			" vtiger_activity.*, vtiger_cntactivityrel.contactid, vtiger_contactdetails.lastname, vtiger_contactdetails.firstname," .
			" vtiger_crmentity.crmid, vtiger_recurringevents.recurringtype, vtiger_crmentity.smownerid, vtiger_crmentity.modifiedtime," .
			" vtiger_seactivityrel.crmid as parent_id " .
			" from vtiger_activity inner join vtiger_seactivityrel on vtiger_seactivityrel.activityid=vtiger_activity.activityid" .
			" inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_activity.activityid" .
			" left join vtiger_cntactivityrel on vtiger_cntactivityrel.activityid = vtiger_activity.activityid " .
			" left join vtiger_contactdetails on vtiger_contactdetails.contactid = vtiger_cntactivityrel.contactid" .
			" left outer join vtiger_recurringevents on vtiger_recurringevents.activityid=vtiger_activity.activityid" .
			" left join vtiger_users on vtiger_users.id=vtiger_crmentity.smownerid" .
			" left join vtiger_groups on vtiger_groups.groupid=vtiger_crmentity.smownerid" .
			" where vtiger_seactivityrel.crmid=" . $id . " and vtiger_crmentity.deleted=0 and (activitytype NOT IN ('Emails'))" .
			" AND ( vtiger_activity.status is NULL OR vtiger_activity.status != 'Completed' )" .
			" and ( vtiger_activity.eventstatus is NULL OR vtiger_activity.eventstatus != 'Held') ";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if ($return_value == null) $return_value = array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

	/**     Function to get the Ticket History information as in array format
	 *	@param int $ticketid - ticket id
	 *	@return array - return an array with holidayname and the ticket history informations in the following format
							array(
								header=>array('0'=>'holidayname'),
								entries=>array('0'=>'info1','1'=>'info2',etc.,)
							     )
	 */
	function get_ticket_history($ticketid)
	{
		global $log, $adb;
		$log->debug("Entering into get_ticket_history($ticketid) method ...");

		$query = "select holidayname,update_log from vtiger_holidaylist where holidaylistid=?";
		$result = $adb->pquery($query, array($ticketid));
		$update_log = $adb->query_result($result, 0, "update_log");

		$splitval = explode('--//--', trim($update_log, '--//--'));

		$header[] = $adb->query_result($result, 0, "holidayname");

		$return_value = array('header' => $header, 'entries' => $splitval);

		$log->debug("Exiting from get_ticket_history($ticketid) method ...");

		return $return_value;
	}
}
