<?php
/* Smarty version 4.5.4, created on 2025-07-09 16:22:03
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vtiger/uitypes/TimeFieldSearchView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_686e972b1779b8_59676858',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '563c0ba255e30ee17f74cdd502f34934ff1f30aa' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vtiger/uitypes/TimeFieldSearchView.tpl',
      1 => 1727649312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_686e972b1779b8_59676858 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('FIELD_INFO', Vtiger_Util_Helper::toSafeHTML(Zend_Json::encode($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldInfo())));
$_smarty_tpl->_assignInScope('SEARCH_VALUE', $_smarty_tpl->tpl_vars['SEARCH_INFO']->value['searchValue']);
$_smarty_tpl->_assignInScope('TIME_FORMAT', $_smarty_tpl->tpl_vars['USER_MODEL']->value->get('hour_format'));?><div class=""><input type="text" data-format="<?php echo $_smarty_tpl->tpl_vars['TIME_FORMAT']->value;?>
" class="timepicker-default listSearchContributor" value="<?php echo $_smarty_tpl->tpl_vars['SEARCH_VALUE']->value;?>
" name="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldName();?>
" data-field-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" data-fieldinfo='<?php echo $_smarty_tpl->tpl_vars['FIELD_INFO']->value;?>
'/></div><?php }
}
