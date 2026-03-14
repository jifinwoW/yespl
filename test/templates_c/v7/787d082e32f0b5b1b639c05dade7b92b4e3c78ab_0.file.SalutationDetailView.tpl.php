<?php
/* Smarty version 4.5.4, created on 2025-03-10 07:57:27
  from '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Vtiger/uitypes/SalutationDetailView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67ce9b67b478f3_49260980',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '787d082e32f0b5b1b639c05dade7b92b4e3c78ab' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Vtiger/uitypes/SalutationDetailView.tpl',
      1 => 1727629512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67ce9b67b478f3_49260980 (Smarty_Internal_Template $_smarty_tpl) {
echo $_smarty_tpl->tpl_vars['RECORD']->value->getDisplayValue('salutationtype');?>


<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue'),$_smarty_tpl->tpl_vars['RECORD']->value->getId(),$_smarty_tpl->tpl_vars['RECORD']->value);
}
}
