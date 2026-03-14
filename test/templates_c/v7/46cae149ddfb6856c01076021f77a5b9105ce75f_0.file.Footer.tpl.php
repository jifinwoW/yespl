<?php
/* Smarty version 4.5.4, created on 2026-01-07 16:48:05
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/ITS4YouInstaller/Footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_695e8e450972a6_13636471',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '46cae149ddfb6856c01076021f77a5b9105ce75f' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/ITS4YouInstaller/Footer.tpl',
      1 => 1740090954,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_695e8e450972a6_13636471 (Smarty_Internal_Template $_smarty_tpl) {
?>
<br><div class="small" style="color: rgb(153, 153, 153);text-align: center;"><?php echo vtranslate($_smarty_tpl->tpl_vars['MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <?php echo ITS4YouInstaller_Version_Helper::$version;?>
 <?php echo vtranslate("COPYRIGHT",$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</div><?php $_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( "Footer.tpl",'Vtiger' )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
}
}
