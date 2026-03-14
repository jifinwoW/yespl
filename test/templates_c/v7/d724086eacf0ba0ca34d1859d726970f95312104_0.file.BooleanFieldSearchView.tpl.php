<?php
/* Smarty version 4.5.4, created on 2025-03-07 15:47:16
  from 'D:\wamp\www\yepcrm\layouts\v7\modules\Users\uitypes\BooleanFieldSearchView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67cb1504a8a430_84137170',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd724086eacf0ba0ca34d1859d726970f95312104' => 
    array (
      0 => 'D:\\wamp\\www\\yepcrm\\layouts\\v7\\modules\\Users\\uitypes\\BooleanFieldSearchView.tpl',
      1 => 1727609712,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67cb1504a8a430_84137170 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('FIELD_INFO', Zend_Json::encode($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldInfo()));
$_smarty_tpl->_assignInScope('SEARCH_VALUES', $_smarty_tpl->tpl_vars['SEARCH_INFO']->value['searchValue']);
$_smarty_tpl->_assignInScope('CHECKED_VALUE', "1");
$_smarty_tpl->_assignInScope('UNCHECKED_VALUE', "0");
if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name') == 'is_admin') {
$_smarty_tpl->_assignInScope('CHECKED_VALUE', "on");
$_smarty_tpl->_assignInScope('UNCHECKED_VALUE', "off");
} elseif ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name') == 'is_owner') {
$_smarty_tpl->_assignInScope('UNCHECKED_VALUE', ' ');
}?><div class=""><select class="select2 listSearchContributor" name="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
" style="width:90px;" data-fieldinfo='<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['FIELD_INFO']->value, ENT_QUOTES, 'UTF-8', true);?>
'><option value=""><?php echo vtranslate('LBL_SELECT_OPTION','Vtiger');?>
</option><option value="<?php echo $_smarty_tpl->tpl_vars['CHECKED_VALUE']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['SEARCH_VALUES']->value == $_smarty_tpl->tpl_vars['CHECKED_VALUE']->value) {?> selected<?php }?>><?php echo vtranslate('LBL_YES',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option><option value="<?php echo $_smarty_tpl->tpl_vars['UNCHECKED_VALUE']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['SEARCH_VALUES']->value == $_smarty_tpl->tpl_vars['UNCHECKED_VALUE']->value) {?> selected<?php }?>><?php echo vtranslate('LBL_NO',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</option></select></div><?php }
}
