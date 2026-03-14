<?php
/* Smarty version 4.5.4, created on 2025-07-05 08:01:35
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vtiger/uitypes/SalutationDetailView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_6868dbdfdebac7_30622941',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a8b9bbacf4e9b78547eba58178fbf6db9188e1ca' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vtiger/uitypes/SalutationDetailView.tpl',
      1 => 1727649312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6868dbdfdebac7_30622941 (Smarty_Internal_Template $_smarty_tpl) {
echo $_smarty_tpl->tpl_vars['RECORD']->value->getDisplayValue('salutationtype');?>


<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue'),$_smarty_tpl->tpl_vars['RECORD']->value->getId(),$_smarty_tpl->tpl_vars['RECORD']->value);
}
}
