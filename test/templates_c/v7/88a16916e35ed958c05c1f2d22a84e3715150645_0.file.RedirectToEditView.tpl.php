<?php
/* Smarty version 4.5.4, created on 2026-03-12 04:07:50
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vtiger/RedirectToEditView.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_69b23c167dfec5_22259541',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '88a16916e35ed958c05c1f2d22a84e3715150645' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vtiger/RedirectToEditView.tpl',
      1 => 1727649312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69b23c167dfec5_22259541 (Smarty_Internal_Template $_smarty_tpl) {
?>
<form id="redirectForm" method="post" action="<?php echo $_smarty_tpl->tpl_vars['REQUEST_URL']->value;?>
" enctype="multipart/form-data"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['REQUEST_DATA']->value, 'FIELD_VALUE', false, 'FIELD_NAME');
$_smarty_tpl->tpl_vars['FIELD_VALUE']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['FIELD_NAME']->value => $_smarty_tpl->tpl_vars['FIELD_VALUE']->value) {
$_smarty_tpl->tpl_vars['FIELD_VALUE']->do_else = false;
if ($_smarty_tpl->tpl_vars['FIELD_NAME']->value == 'returnrelatedModule') {
$_smarty_tpl->_assignInScope('FIELD_NAME', 'returnrelatedModuleName');
}
if (is_array($_smarty_tpl->tpl_vars['FIELD_VALUE']->value)) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['FIELD_VALUE']->value, 'VALUE', false, 'KEY');
$_smarty_tpl->tpl_vars['VALUE']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['KEY']->value => $_smarty_tpl->tpl_vars['VALUE']->value) {
$_smarty_tpl->tpl_vars['VALUE']->do_else = false;
if (is_array($_smarty_tpl->tpl_vars['VALUE']->value)) {
$_smarty_tpl->_assignInScope('VALUE', Zend_Json::encode($_smarty_tpl->tpl_vars['VALUE']->value));?><input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['FIELD_NAME']->value;?>
[<?php echo $_smarty_tpl->tpl_vars['KEY']->value;?>
]" value='<?php echo $_smarty_tpl->tpl_vars['VALUE']->value;?>
'><?php } else { ?><input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['FIELD_NAME']->value;?>
[<?php echo $_smarty_tpl->tpl_vars['KEY']->value;?>
]" value="<?php echo htmlentities($_smarty_tpl->tpl_vars['VALUE']->value);?>
"><?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
} elseif ($_smarty_tpl->tpl_vars['FIELD_NAME']->value == 'notecontent') {?><input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['FIELD_NAME']->value;?>
" value='<?php echo decode_html($_smarty_tpl->tpl_vars['FIELD_VALUE']->value);?>
' ><?php } else { ?><input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['FIELD_NAME']->value;?>
" value="<?php echo htmlentities($_smarty_tpl->tpl_vars['FIELD_VALUE']->value);?>
"><?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></form>
		<?php echo '<script'; ?>
 type="text/javascript" src="libraries/jquery/jquery.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#redirectForm').submit();
			});
		<?php echo '</script'; ?>
>
	<?php }
}
