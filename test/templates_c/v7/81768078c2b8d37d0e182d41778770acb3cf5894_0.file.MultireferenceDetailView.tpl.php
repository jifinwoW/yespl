<?php
/* Smarty version 4.5.4, created on 2025-04-22 16:11:00
  from '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Events/uitypes/MultireferenceDetailView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_6807bf947a6c49_39892280',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '81768078c2b8d37d0e182d41778770acb3cf5894' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Events/uitypes/MultireferenceDetailView.tpl',
      1 => 1727629512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6807bf947a6c49_39892280 (Smarty_Internal_Template $_smarty_tpl) {
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
