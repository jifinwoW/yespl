<?php
/* Smarty version 4.5.4, created on 2025-03-10 07:57:29
  from '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Vtiger/SummaryWidgets.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67ce9b690ff148_09645697',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4e457f2140374a6e4fbe123d04ef1ed2db465cae' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Vtiger/SummaryWidgets.tpl',
      1 => 1727629512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67ce9b690ff148_09645697 (Smarty_Internal_Template $_smarty_tpl) {
?>
<input type="hidden" name="page" value="<?php echo $_smarty_tpl->tpl_vars['PAGING_MODEL']->value->get('page');?>
" /><input type="hidden" name="pageLimit" value="<?php echo $_smarty_tpl->tpl_vars['PAGING_MODEL']->value->get('limit');?>
" /><?php if ($_smarty_tpl->tpl_vars['RELATED_MODULE']->value && $_smarty_tpl->tpl_vars['RELATED_RECORDS']->value) {
$_smarty_tpl->_assignInScope('FILENAME', ($_smarty_tpl->tpl_vars['RELATED_MODULE']->value).("SummaryWidgetContents.tpl"));
$_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( $_smarty_tpl->tpl_vars['FILENAME']->value,$_smarty_tpl->tpl_vars['MODULE']->value )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('RELATED_RECORDS'=>$_smarty_tpl->tpl_vars['RELATED_RECORDS']->value), 0, true);
} else { ?><div class="summaryWidgetContainer noContent"><p class="textAlignCenter"><?php echo vtranslate('LBL_NO_RELATED',$_smarty_tpl->tpl_vars['MODULE']->value);?>
 <?php echo $_smarty_tpl->tpl_vars['RELATED_MODULE']->value;?>
</p></div><?php }
}
}
