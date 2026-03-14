<?php
/* Smarty version 4.5.4, created on 2025-03-04 05:02:00
  from 'D:\wamp\www\yepcrm\layouts\v7\modules\Vtiger\uitypes\FieldSearchView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67c6894808b898_57166992',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cf26bdcb53e252c8486393488e9625ff271e9b5e' => 
    array (
      0 => 'D:\\wamp\\www\\yepcrm\\layouts\\v7\\modules\\Vtiger\\uitypes\\FieldSearchView.tpl',
      1 => 1727609712,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67c6894808b898_57166992 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('FIELD_INFO', Zend_Json::encode($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldInfo()));?><div class=""><input type="text" name="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
" class="listSearchContributor inputElement" value="<?php if ((isset($_smarty_tpl->tpl_vars['SEARCH_INFO']->value['searchValue']))) {
echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['SEARCH_INFO']->value['searchValue'], ENT_QUOTES, 'UTF-8', true);
}?>" data-field-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" data-fieldinfo='<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['FIELD_INFO']->value, ENT_QUOTES, 'UTF-8', true);?>
'/></div>
<?php }
}
