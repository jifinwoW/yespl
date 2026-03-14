<?php
/* Smarty version 4.5.4, created on 2025-03-12 16:19:25
  from '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Vtiger/uitypes/ReminderDetailView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67d1b40d726376_26694458',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '18fbe7b383150f9a30370372e0bae3d2754b47b4' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Vtiger/uitypes/ReminderDetailView.tpl',
      1 => 1727629512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67d1b40d726376_26694458 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('REMINDER_VALUES', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue'),$_smarty_tpl->tpl_vars['RECORD']->value->getId()));
if ($_smarty_tpl->tpl_vars['REMINDER_VALUES']->value == '') {?>
    <?php echo vtranslate('LBL_NO',$_smarty_tpl->tpl_vars['MODULE']->value);?>

<?php } else { ?>
    <?php echo $_smarty_tpl->tpl_vars['REMINDER_VALUES']->value;
echo vtranslate('LBL_BEFORE_EVENT',$_smarty_tpl->tpl_vars['MODULE']->value);?>

<?php }
}
}
