<?php
/* Smarty version 4.5.4, created on 2026-01-17 22:50:18
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Reports/EditHeader.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_696c122a7f9b22_47602569',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4a4d2b24ee9924dc2243d52635cb93f5915f6fa3' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Reports/EditHeader.tpl',
      1 => 1727649312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_696c122a7f9b22_47602569 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="editContainer" style="padding-left: 2%;padding-right: 2%"><div class="row"><?php $_smarty_tpl->_assignInScope('LABELS', array("step1"=>"LBL_REPORT_DETAILS","step2"=>"LBL_SELECT_COLUMNS","step3"=>"LBL_FILTERS"));
$_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( "BreadCrumbs.tpl",$_smarty_tpl->tpl_vars['MODULE']->value )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('ACTIVESTEP'=>1,'BREADCRUMB_LABELS'=>$_smarty_tpl->tpl_vars['LABELS']->value,'MODULE'=>$_smarty_tpl->tpl_vars['MODULE']->value), 0, true);
?></div><div class="clearfix"></div><?php }
}
