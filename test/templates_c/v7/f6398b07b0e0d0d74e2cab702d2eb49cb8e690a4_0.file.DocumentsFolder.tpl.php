<?php
/* Smarty version 4.5.4, created on 2025-04-03 17:29:17
  from '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Vtiger/uitypes/DocumentsFolder.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67eec56db86f28_25097247',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f6398b07b0e0d0d74e2cab702d2eb49cb8e690a4' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Vtiger/uitypes/DocumentsFolder.tpl',
      1 => 1727629512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67eec56db86f28_25097247 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('FIELD_INFO', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldInfo());
$_smarty_tpl->_assignInScope('FOLDER_VALUES', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getDocumentFolders());
$_smarty_tpl->_assignInScope('SPECIAL_VALIDATOR', $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getValidator());?><select class="select2 inputElement" name="<?php echo $_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getFieldName();?>
" <?php if (!empty($_smarty_tpl->tpl_vars['SPECIAL_VALIDATOR']->value)) {?>data-validator="<?php echo Zend_Json::encode($_smarty_tpl->tpl_vars['SPECIAL_VALIDATOR']->value);?>
"<?php }
if ($_smarty_tpl->tpl_vars['FIELD_INFO']->value["mandatory"] == true) {?> data-rule-required="true" <?php }
if (php7_count($_smarty_tpl->tpl_vars['FIELD_INFO']->value['validator'])) {?>data-specific-rules='<?php echo ZEND_JSON::encode($_smarty_tpl->tpl_vars['FIELD_INFO']->value["validator"]);?>
'<?php }?>><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['FOLDER_VALUES']->value, 'FOLDER_NAME', false, 'FOLDER_VALUE');
$_smarty_tpl->tpl_vars['FOLDER_NAME']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['FOLDER_VALUE']->value => $_smarty_tpl->tpl_vars['FOLDER_NAME']->value) {
$_smarty_tpl->tpl_vars['FOLDER_NAME']->do_else = false;
?><option value="<?php echo $_smarty_tpl->tpl_vars['FOLDER_VALUE']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->get('fieldvalue') == $_smarty_tpl->tpl_vars['FOLDER_VALUE']->value) {?> selected <?php }?>><?php echo $_smarty_tpl->tpl_vars['FOLDER_NAME']->value;?>
</option><?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></select><?php }
}
