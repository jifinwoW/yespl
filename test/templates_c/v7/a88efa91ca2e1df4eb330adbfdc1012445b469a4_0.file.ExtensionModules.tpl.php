<?php
/* Smarty version 4.5.4, created on 2026-01-07 16:48:04
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Settings/ITS4YouInstaller/ExtensionModules.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_695e8e44bcd7f4_43751342',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a88efa91ca2e1df4eb330adbfdc1012445b469a4' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Settings/ITS4YouInstaller/ExtensionModules.tpl',
      1 => 1740090954,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_695e8e44bcd7f4_43751342 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('IS_AUTH', ($_smarty_tpl->tpl_vars['REGISTRATION_STATUS']->value && $_smarty_tpl->tpl_vars['PASSWORD_STATUS']->value));?><div class="col-lg-12"><br><ul class="nav nav-tabs layoutTabs massEditTabs"><li class="tab-item taxesTab active"><a data-toggle="tab" href="#installedModules"><strong><?php echo vtranslate('LBL_INSTALLED_AND_AVAILABLE_MODULES',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong></a></li><?php if (!$_smarty_tpl->tpl_vars['IS_HOSTING_LICENSE']->value) {?><li class="tab-item chargesTab"><a data-toggle="tab" href="#modulesShop"><strong><?php echo vtranslate('LBL_MODULES_SHOP',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong></a></li><?php }?></ul><br></div><div class="tab-content layoutContent overflowVisible" style="height:100%"><?php $_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( "InstalledModules.tpl",$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
if (!$_smarty_tpl->tpl_vars['IS_HOSTING_LICENSE']->value) {
$_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( "ModulesShop.tpl",$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}?></div><?php }
}
