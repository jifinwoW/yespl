<?php
/* Smarty version 4.5.4, created on 2025-03-05 04:58:36
  from 'D:\wamp\www\yepcrm\layouts\v7\modules\Accounts\DetailViewHeaderTitle.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67c7d9fc464722_43508211',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '11c7a96f97eaa788594bd9c8c11bbcc57765cd58' => 
    array (
      0 => 'D:\\wamp\\www\\yepcrm\\layouts\\v7\\modules\\Accounts\\DetailViewHeaderTitle.tpl',
      1 => 1727609712,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67c7d9fc464722_43508211 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="col-sm-6"><div class="clearfix record-header "><div class="recordImage bgAccounts app-<?php echo $_smarty_tpl->tpl_vars['SELECTED_MENU_CATEGORY']->value;?>
"><?php $_smarty_tpl->_assignInScope('IMAGE_DETAILS', $_smarty_tpl->tpl_vars['RECORD']->value->getImageDetails());
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['IMAGE_DETAILS']->value, 'IMAGE_INFO', false, 'ITER');
$_smarty_tpl->tpl_vars['IMAGE_INFO']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['ITER']->value => $_smarty_tpl->tpl_vars['IMAGE_INFO']->value) {
$_smarty_tpl->tpl_vars['IMAGE_INFO']->do_else = false;
if (!empty($_smarty_tpl->tpl_vars['IMAGE_INFO']->value['url'])) {?><img src="<?php echo $_smarty_tpl->tpl_vars['IMAGE_INFO']->value['url'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['IMAGE_INFO']->value['orgname'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['IMAGE_INFO']->value['orgname'];?>
" width="100%" height="100%" align="left"><br><?php } else { ?><img src="<?php echo vimage_path('summary_organizations.png');?>
" class="summaryImg"/><?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
if (empty($_smarty_tpl->tpl_vars['IMAGE_DETAILS']->value)) {?><div class="name"><span><strong><?php echo $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getModuleIcon();?>
</strong></span></div><?php }?></div><div class="recordBasicInfo"><div class="info-row" ><h4><span class="recordLabel pushDown" title="<?php echo $_smarty_tpl->tpl_vars['RECORD']->value->getName();?>
"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getNameFields(), 'NAME_FIELD');
$_smarty_tpl->tpl_vars['NAME_FIELD']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['NAME_FIELD']->value) {
$_smarty_tpl->tpl_vars['NAME_FIELD']->do_else = false;
$_smarty_tpl->_assignInScope('FIELD_MODEL', $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getField($_smarty_tpl->tpl_vars['NAME_FIELD']->value));
if ($_smarty_tpl->tpl_vars['FIELD_MODEL']->value->getPermissions()) {?><span class="<?php echo $_smarty_tpl->tpl_vars['NAME_FIELD']->value;?>
"><?php echo trim($_smarty_tpl->tpl_vars['RECORD']->value->get($_smarty_tpl->tpl_vars['NAME_FIELD']->value));?>
</span>&nbsp;<?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></span></h4></div><?php $_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( "DetailViewHeaderFieldsView.tpl",$_smarty_tpl->tpl_vars['MODULE']->value )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?><div class="info-row"><i class="fa fa-map-marker"></i>&nbsp;<a class="showMap" href="javascript:void(0);" onclick='Vtiger_Index_Js.showMap(this);' data-module='<?php echo $_smarty_tpl->tpl_vars['RECORD']->value->getModule()->getName();?>
' data-record='<?php echo $_smarty_tpl->tpl_vars['RECORD']->value->getId();?>
'><?php echo vtranslate('LBL_SHOW_MAP',$_smarty_tpl->tpl_vars['MODULE_NAME']->value);?>
</a></div></div></div></div><?php }
}
