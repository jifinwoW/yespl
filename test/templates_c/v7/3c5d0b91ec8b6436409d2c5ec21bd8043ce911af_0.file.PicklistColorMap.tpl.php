<?php
/* Smarty version 4.5.4, created on 2025-03-04 05:01:59
  from 'D:\wamp\www\yepcrm\layouts\v7\modules\Vtiger\PicklistColorMap.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67c689473a5db0_06590392',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3c5d0b91ec8b6436409d2c5ec21bd8043ce911af' => 
    array (
      0 => 'D:\\wamp\\www\\yepcrm\\layouts\\v7\\modules\\Vtiger\\PicklistColorMap.tpl',
      1 => 1727609712,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67c689473a5db0_06590392 (Smarty_Internal_Template $_smarty_tpl) {
?>
<style type="text/css">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['LISTVIEW_HEADERS']->value, 'FIELD_MODEL', false, 'FIELD_NAME');
$_smarty_tpl->tpl_vars['FIELD_MODEL']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['FIELD_NAME']->value => $_smarty_tpl->tpl_vars['FIELD_MODEL']->value) {
$_smarty_tpl->tpl_vars['FIELD_MODEL']->do_else = false;
?>
        <?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType() == 'picklist' || $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType() == 'multipicklist') {?>
            <?php $_smarty_tpl->_assignInScope('FIELD_NAME', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('_name'));?>
            <?php if ($_smarty_tpl->tpl_vars['FIELD_NAME']->value == '') {?>
                <?php $_smarty_tpl->_assignInScope('FIELD_NAME', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getName());?>
            <?php }?>
            <?php $_smarty_tpl->_assignInScope('PICKLIST_COLOR_MAP', Settings_Picklist_Module_Model::getPicklistColorMap($_smarty_tpl->tpl_vars['FIELD_NAME']->value,true));?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['PICKLIST_COLOR_MAP']->value, 'PICKLIST_COLOR', false, 'PICKLIST_VALUE');
$_smarty_tpl->tpl_vars['PICKLIST_COLOR']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['PICKLIST_VALUE']->value => $_smarty_tpl->tpl_vars['PICKLIST_COLOR']->value) {
$_smarty_tpl->tpl_vars['PICKLIST_COLOR']->do_else = false;
?>
                <?php $_smarty_tpl->_assignInScope('PICKLIST_TEXT_COLOR', decode_html(Settings_Picklist_Module_Model::getTextColor($_smarty_tpl->tpl_vars['PICKLIST_COLOR']->value)));?>
                <?php $_smarty_tpl->_assignInScope('CONVERTED_PICKLIST_VALUE', Vtiger_Util_Helper::convertSpaceToHyphen($_smarty_tpl->tpl_vars['PICKLIST_VALUE']->value));?>
                    .picklist-<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getId();?>
-<?php echo Vtiger_Util_Helper::escapeCssSpecialCharacters($_smarty_tpl->tpl_vars['CONVERTED_PICKLIST_VALUE']->value);?>
 {
                        background-color: <?php echo $_smarty_tpl->tpl_vars['PICKLIST_COLOR']->value;?>
;
                        color: <?php echo $_smarty_tpl->tpl_vars['PICKLIST_TEXT_COLOR']->value;?>
; 
                    }
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <?php }?>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <?php if (($_smarty_tpl->tpl_vars['MODULE_NAME']->value == 'Calendar' && $_smarty_tpl->tpl_vars['MODULE_MODEL']->value) || ($_smarty_tpl->tpl_vars['RELATED_MODULE_NAME']->value == 'Calendar' && $_smarty_tpl->tpl_vars['RELATED_MODULE']->value) || ($_smarty_tpl->tpl_vars['SOURCE_MODULE']->value == 'Calendar')) {?>
		<?php $_smarty_tpl->_assignInScope('STATUS_FIELD', Vtiger_Field_Model::getInstance('eventstatus',Vtiger_Module_Model::getInstance('Events')));?>

        <?php if ($_smarty_tpl->tpl_vars['STATUS_FIELD']->value) {?>
            <?php $_smarty_tpl->_assignInScope('PICKLIST_COLOR_MAP', Settings_Picklist_Module_Model::getPicklistColorMap('eventstatus',true));?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['PICKLIST_COLOR_MAP']->value, 'PICKLIST_COLOR', false, 'PICKLIST_VALUE');
$_smarty_tpl->tpl_vars['PICKLIST_COLOR']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['PICKLIST_VALUE']->value => $_smarty_tpl->tpl_vars['PICKLIST_COLOR']->value) {
$_smarty_tpl->tpl_vars['PICKLIST_COLOR']->do_else = false;
?>
                <?php $_smarty_tpl->_assignInScope('PICKLIST_TEXT_COLOR', Settings_Picklist_Module_Model::getTextColor($_smarty_tpl->tpl_vars['PICKLIST_COLOR']->value));?>
                <?php $_smarty_tpl->_assignInScope('CONVERTED_PICKLIST_VALUE', Vtiger_Util_Helper::convertSpaceToHyphen($_smarty_tpl->tpl_vars['PICKLIST_VALUE']->value));?>
                    .picklist-<?php echo $_smarty_tpl->tpl_vars['STATUS_FIELD']->value->getId();?>
-<?php echo Vtiger_Util_Helper::escapeCssSpecialCharacters($_smarty_tpl->tpl_vars['CONVERTED_PICKLIST_VALUE']->value);?>
 {
                        background-color: <?php echo $_smarty_tpl->tpl_vars['PICKLIST_COLOR']->value;?>
;color: <?php echo $_smarty_tpl->tpl_vars['PICKLIST_TEXT_COLOR']->value;?>
;
                    }
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        <?php }?>
    <?php }?>
</style>
<?php }
}
