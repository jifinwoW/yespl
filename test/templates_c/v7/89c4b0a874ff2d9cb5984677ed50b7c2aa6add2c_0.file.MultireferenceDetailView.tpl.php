<?php
/* Smarty version 4.5.4, created on 2025-09-01 07:09:02
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Events/uitypes/MultireferenceDetailView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_68b5468ea41ef2_77416579',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '89c4b0a874ff2d9cb5984677ed50b7c2aa6add2c' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Events/uitypes/MultireferenceDetailView.tpl',
      1 => 1727649312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68b5468ea41ef2_77416579 (Smarty_Internal_Template $_smarty_tpl) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['RELATED_CONTACTS']->value, 'CONTACT_INFO');
$_smarty_tpl->tpl_vars['CONTACT_INFO']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['CONTACT_INFO']->value) {
$_smarty_tpl->tpl_vars['CONTACT_INFO']->do_else = false;
?><a href='<?php echo $_smarty_tpl->tpl_vars['CONTACT_INFO']->value['_model']->getDetailViewUrl();?>
' title='<?php echo vtranslate("Contacts","Contacts");?>
'> <?php echo Vtiger_Util_Helper::getRecordName($_smarty_tpl->tpl_vars['CONTACT_INFO']->value['id']);?>
</a><br><?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
