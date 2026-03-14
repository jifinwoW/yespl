<?php
/* Smarty version 4.5.4, created on 2026-01-26 07:39:34
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Import/Import_Result_Details.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_69771a363c79f0_18260129',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '18021e1ac4501764de580b4fbcf330e4f17deed7' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Import/Import_Result_Details.tpl',
      1 => 1739207400,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69771a363c79f0_18260129 (Smarty_Internal_Template $_smarty_tpl) {
?>
<table width="100%" class="table table-borderless">
	<tr>
		<td class = "greenColor"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtranslate' ][ 0 ], array( 'LBL_TOTAL_RECORDS_IMPORTED',$_smarty_tpl->tpl_vars['MODULE']->value ));?>
</td>
		<td width="10%">:</td>
		<td class = "greenColor" width="30%"><?php echo $_smarty_tpl->tpl_vars['IMPORT_RESULT']->value['IMPORTED'];?>
 / <?php echo $_smarty_tpl->tpl_vars['IMPORT_RESULT']->value['TOTAL'];?>
</td>
	</tr>
	<tr>
		<td class = "greenColor" width="20%"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtranslate' ][ 0 ], array( 'LBL_NUMBER_OF_RECORDS_CREATED',$_smarty_tpl->tpl_vars['MODULE']->value ));?>
</td>
		<td width="1%">:</td>
		<td  class = "greenColor" width="60%"><?php echo $_smarty_tpl->tpl_vars['IMPORT_RESULT']->value['CREATED'];?>

			<?php if ($_smarty_tpl->tpl_vars['IMPORT_RESULT']->value['CREATED'] != '0') {?>
				<?php if ($_smarty_tpl->tpl_vars['FOR_MODULE']->value != 'Users') {?>
					&nbsp;&nbsp;&nbsp;&nbsp;<a class="cursorPointer" onclick="return Vtiger_Import_Js.showLastImportedRecords('index.php?module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&for_module=<?php echo $_smarty_tpl->tpl_vars['FOR_MODULE']->value;?>
&view=List&start=1&foruser=<?php echo $_smarty_tpl->tpl_vars['OWNER_ID']->value;?>
&_showContents=0')"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtranslate' ][ 0 ], array( 'LBL_DETAILS',$_smarty_tpl->tpl_vars['MODULE']->value ));?>
</a>
				<?php }?>
			<?php }?>
		</td>

	</tr>
	<?php if (in_array($_smarty_tpl->tpl_vars['FOR_MODULE']->value,$_smarty_tpl->tpl_vars['INVENTORY_MODULES']->value) == FALSE) {?>
		<tr>
			<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtranslate' ][ 0 ], array( 'LBL_NUMBER_OF_RECORDS_UPDATED',$_smarty_tpl->tpl_vars['MODULE']->value ));?>
</td>
			<td width="10%">:</td>
			<td width="30%"><?php echo $_smarty_tpl->tpl_vars['IMPORT_RESULT']->value['UPDATED'];?>
</td>
		</tr>
		<tr>
			<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtranslate' ][ 0 ], array( 'LBL_NUMBER_OF_RECORDS_SKIPPED',$_smarty_tpl->tpl_vars['MODULE']->value ));?>
</td>
			<td width="10%">:</td>
			<td width="30%"><?php echo $_smarty_tpl->tpl_vars['IMPORT_RESULT']->value['SKIPPED'];?>

				<?php if ($_smarty_tpl->tpl_vars['IMPORT_RESULT']->value['SKIPPED'] != '0') {?>
					&nbsp;&nbsp;&nbsp;&nbsp;<a class="cursorPointer" onclick="return Vtiger_Import_Js.showSkippedRecords('index.php?module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&view=List&mode=getImportDetails&type=skipped&start=1&foruser=<?php echo $_smarty_tpl->tpl_vars['OWNER_ID']->value;?>
&_showContents=0&for_module=<?php echo $_smarty_tpl->tpl_vars['FOR_MODULE']->value;?>
')"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtranslate' ][ 0 ], array( 'LBL_DETAILS',$_smarty_tpl->tpl_vars['MODULE']->value ));?>
</a>
				<?php }?>
			</td>
		</tr>
		<tr>
			<td><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtranslate' ][ 0 ], array( 'LBL_NUMBER_OF_RECORDS_MERGED',$_smarty_tpl->tpl_vars['MODULE']->value ));?>
</td>
			<td width="10%">:</td>
			<td width="10%"><?php echo $_smarty_tpl->tpl_vars['IMPORT_RESULT']->value['MERGED'];?>
</td>
		</tr>
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['IMPORT_RESULT']->value['FAILED'] != '0') {?>
		<tr>
			<td><font color = "red"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtranslate' ][ 0 ], array( 'LBL_TOTAL_RECORDS_FAILED',$_smarty_tpl->tpl_vars['MODULE']->value ));?>
</font></td>
			<td width="10%">:</td>
			<td width="30%"><font color = "red"><?php echo $_smarty_tpl->tpl_vars['IMPORT_RESULT']->value['FAILED'];?>
 / <?php echo $_smarty_tpl->tpl_vars['IMPORT_RESULT']->value['TOTAL'];?>
</font>
				&nbsp;&nbsp;&nbsp;&nbsp;<a class="cursorPointer" onclick="return Vtiger_Import_Js.showFailedImportRecords('index.php?module=<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
&view=List&mode=getImportDetails&type=failed&start=1&foruser=<?php echo $_smarty_tpl->tpl_vars['OWNER_ID']->value;?>
&_showContents=0&for_module=<?php echo $_smarty_tpl->tpl_vars['FOR_MODULE']->value;?>
')"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtranslate' ][ 0 ], array( 'LBL_DETAILS',$_smarty_tpl->tpl_vars['MODULE']->value ));?>
</a>
			</td>
		</tr>
	<?php }?>
</table><?php }
}
