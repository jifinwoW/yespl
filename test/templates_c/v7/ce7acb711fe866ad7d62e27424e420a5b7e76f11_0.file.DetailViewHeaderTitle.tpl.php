<?php
/* Smarty version 4.5.4, created on 2026-03-11 08:10:32
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vendors/DetailViewHeaderTitle.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_69b123783efae5_75683736',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ce7acb711fe866ad7d62e27424e420a5b7e76f11' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vendors/DetailViewHeaderTitle.tpl',
      1 => 1727649312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69b123783efae5_75683736 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="col-lg-6 col-md-6 col-sm-6"><div class="record-header clearfix"><div class="recordImage bgvendors app-<?php echo $_smarty_tpl->tpl_vars['SELECTED_MENU_CATEGORY']->value;?>
"><div class="name"><span><strong><?php echo $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getModuleIcon();?>
</strong></span></div></div><div class="recordBasicInfo"><div class="info-row"><h4><span class="recordLabel pushDown" title="<?php echo $_smarty_tpl->tpl_vars['RECORD']->value->getName();?>
"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getNameFields(), 'NAME_FIELD');
$_smarty_tpl->tpl_vars['NAME_FIELD']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['NAME_FIELD']->value) {
$_smarty_tpl->tpl_vars['NAME_FIELD']->do_else = false;
$_smarty_tpl->_assignInScope('FIELD_MODEL', $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getField($_smarty_tpl->tpl_vars['NAME_FIELD']->value));
if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getPermissions()) {?><span class="<?php echo $_smarty_tpl->tpl_vars['NAME_FIELD']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['RECORD']->value->get($_smarty_tpl->tpl_vars['NAME_FIELD']->value);?>
</span>&nbsp;<?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></span></h4></div><?php $_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( "DetailViewHeaderFieldsView.tpl",$_smarty_tpl->tpl_vars['MODULE']->value )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?></div></div></div><?php }
}
