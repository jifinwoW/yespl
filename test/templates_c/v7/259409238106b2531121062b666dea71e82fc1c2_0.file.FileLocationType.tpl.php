<?php
/* Smarty version 4.5.4, created on 2025-03-04 05:05:11
  from 'D:\wamp\www\yepcrm\layouts\v7\modules\Vtiger\uitypes\FileLocationType.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67c68a0733a336_16614990',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '259409238106b2531121062b666dea71e82fc1c2' => 
    array (
      0 => 'D:\\wamp\\www\\yepcrm\\layouts\\v7\\modules\\Vtiger\\uitypes\\FileLocationType.tpl',
      1 => 1727609712,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67c68a0733a336_16614990 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('FIELD_VALUES', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFileLocationType());?><select class="select2" name="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldName();?>
" <?php if ((isset($_smarty_tpl->tpl_vars['FILE_LOCATION_TYPE']->value)) && ($_smarty_tpl->tpl_vars['FILE_LOCATION_TYPE']->value == 'I' || $_smarty_tpl->tpl_vars['FILE_LOCATION_TYPE']->value == 'E')) {?> disabled <?php }?>><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['FIELD_VALUES']->value, 'TYPE', false, 'KEY');
$_smarty_tpl->tpl_vars['TYPE']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['KEY']->value => $_smarty_tpl->tpl_vars['TYPE']->value) {
$_smarty_tpl->tpl_vars['TYPE']->do_else = false;
if ((isset($_smarty_tpl->tpl_vars['FILE_LOCATION_TYPE']->value)) && $_smarty_tpl->tpl_vars['FILE_LOCATION_TYPE']->value == 'I') {
$_smarty_tpl->_assignInScope('SELECTED', 'I');
} elseif ((isset($_smarty_tpl->tpl_vars['FILE_LOCATION_TYPE']->value)) && $_smarty_tpl->tpl_vars['FILE_LOCATION_TYPE']->value == 'E') {
$_smarty_tpl->_assignInScope('SELECTED', 'E');
} else {
$_smarty_tpl->_assignInScope('SELECTED', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue'));
}?><option value="<?php echo $_smarty_tpl->tpl_vars['KEY']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['SELECTED']->value == $_smarty_tpl->tpl_vars['KEY']->value) {?> selected <?php }?>><?php echo vtranslate($_smarty_tpl->tpl_vars['TYPE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></select><?php }
}
