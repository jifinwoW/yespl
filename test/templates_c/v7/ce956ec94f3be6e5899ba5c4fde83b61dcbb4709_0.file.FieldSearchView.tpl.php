<?php
/* Smarty version 4.5.4, created on 2025-07-05 07:00:58
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vtiger/uitypes/FieldSearchView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_6868cdaa34b558_59366778',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ce956ec94f3be6e5899ba5c4fde83b61dcbb4709' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vtiger/uitypes/FieldSearchView.tpl',
      1 => 1727649312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6868cdaa34b558_59366778 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('FIELD_INFO', Zend_Json::encode($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldInfo()));?><div class=""><input type="text" name="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('name');?>
" class="listSearchContributor inputElement" value="<?php if ((isset($_smarty_tpl->tpl_vars['SEARCH_INFO']->value['searchValue']))) {
echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['SEARCH_INFO']->value['searchValue'], ENT_QUOTES, 'UTF-8', true);
}?>" data-field-type="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldDataType();?>
" data-fieldinfo='<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['FIELD_INFO']->value, ENT_QUOTES, 'UTF-8', true);?>
'/></div>
<?php }
}
