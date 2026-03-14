<?php
/* Smarty version 4.5.4, created on 2025-03-06 04:30:39
  from 'D:\wamp\www\yepcrm\layouts\v7\modules\Vtiger\uitypes\ReminderDetailView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67c924ef480110_41783307',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd408622c2fbb0aa09927b583d8a2c766fd1d08ff' => 
    array (
      0 => 'D:\\wamp\\www\\yepcrm\\layouts\\v7\\modules\\Vtiger\\uitypes\\ReminderDetailView.tpl',
      1 => 1727609712,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67c924ef480110_41783307 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('REMINDER_VALUES', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDisplayValue($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue'),$_smarty_tpl->tpl_vars['RECORD']->value->getId()));
if ($_smarty_tpl->tpl_vars['REMINDER_VALUES']->value == '') {?>
    <?php echo vtranslate('LBL_NO',$_smarty_tpl->tpl_vars['MODULE']->value);?>

<?php } else { ?>
    <?php echo $_smarty_tpl->tpl_vars['REMINDER_VALUES']->value;
echo vtranslate('LBL_BEFORE_EVENT',$_smarty_tpl->tpl_vars['MODULE']->value);?>

<?php }
}
}
