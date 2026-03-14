<?php
/* Smarty version 4.5.4, created on 2025-09-01 07:09:02
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vtiger/uitypes/ReminderDetailView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_68b5468ea05a51_14630465',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2f19bf066d10f409b940363dab513bf9007a2acd' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vtiger/uitypes/ReminderDetailView.tpl',
      1 => 1727649312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68b5468ea05a51_14630465 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('REMINDER_VALUES', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue'),$_smarty_tpl->tpl_vars['RECORD']->value->getId()));
if ($_smarty_tpl->tpl_vars['REMINDER_VALUES']->value == '') {?>
    <?php echo vtranslate('LBL_NO',$_smarty_tpl->tpl_vars['MODULE']->value);?>

<?php } else { ?>
    <?php echo $_smarty_tpl->tpl_vars['REMINDER_VALUES']->value;
echo vtranslate('LBL_BEFORE_EVENT',$_smarty_tpl->tpl_vars['MODULE']->value);?>

<?php }
}
}
