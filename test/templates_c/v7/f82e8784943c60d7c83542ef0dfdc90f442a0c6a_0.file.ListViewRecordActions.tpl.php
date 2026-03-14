<?php
/* Smarty version 4.5.4, created on 2025-03-17 07:22:16
  from '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Settings/Workflows/ListViewRecordActions.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67d7cda8c6c3f7_93111475',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f82e8784943c60d7c83542ef0dfdc90f442a0c6a' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Settings/Workflows/ListViewRecordActions.tpl',
      1 => 1727629512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67d7cda8c6c3f7_93111475 (Smarty_Internal_Template $_smarty_tpl) {
?><!--LIST VIEW RECORD ACTIONS--><div style="width:80px ;"><a class="deleteRecordButton" style=" opacity: 0; padding: 0 5px;"><i title="<?php echo vtranslate('LBL_DELETE',$_smarty_tpl->tpl_vars['MODULE']->value);?>
" class="fa fa-trash alignMiddle"></i></a><input style="opacity: 0;" <?php if ($_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->get('status')) {?> checked value="on" <?php } else { ?> value="off"<?php }?> data-on-color="success"  data-id="<?php echo $_smarty_tpl->tpl_vars['LISTVIEW_ENTRY']->value->getId();?>
" type="checkbox" name="workflowstatus" id="workflowstatus"></div><?php }
}
