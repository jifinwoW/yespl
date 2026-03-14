<?php
/* Smarty version 4.5.4, created on 2026-01-26 08:30:24
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Users/TransferOwnership.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_697726202d8663_17499195',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4f5deb232db07db12c7417c216bd91841685d7c9' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Users/TransferOwnership.tpl',
      1 => 1727649312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_697726202d8663_17499195 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="modal-dialog modelContainer"'><?php ob_start();
echo vtranslate('LBL_TRANSFER_OWNERSHIP',$_smarty_tpl->tpl_vars['MODULE']->value);
$_prefixVariable1 = ob_get_clean();
$_smarty_tpl->_assignInScope('HEADER_TITLE', $_prefixVariable1);
$_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( "ModalHeader.tpl",$_smarty_tpl->tpl_vars['MODULE']->value )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('TITLE'=>$_smarty_tpl->tpl_vars['HEADER_TITLE']->value), 0, true);
?><div class="modal-content"><form class="form-horizontal" id="transferOwner" method="post"><input type="hidden" name="module" value="<?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
"><input type="hidden" name="action" value="SaveAjax"><input type="hidden" name="mode" value="transferOwner"><div name='massEditContent'><div class="modal-body"><div class="form-group"><label class="control-label fieldLabel col-sm-5"><?php echo vtranslate('LBL_TRANSFER_OWNERSHIP_TO_USER',$_smarty_tpl->tpl_vars['MODULE']->value);?>
</label><div class="controls fieldValue col-xs-6"><select class="select2" name="record" style="width: 50%;"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['USERS_MODEL']->value, 'USER_MODEL', false, 'USER_ID');
$_smarty_tpl->tpl_vars['USER_MODEL']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['USER_ID']->value => $_smarty_tpl->tpl_vars['USER_MODEL']->value) {
$_smarty_tpl->tpl_vars['USER_MODEL']->do_else = false;
?><option value="<?php echo $_smarty_tpl->tpl_vars['USER_ID']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['USER_MODEL']->value->getDisplayName();?>
</option><?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></select></div></div></div></div><?php $_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( 'ModalFooter.tpl',$_smarty_tpl->tpl_vars['MODULE']->value )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?></form></div></div>

<?php }
}
