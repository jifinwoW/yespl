<?php
/* Smarty version 4.5.4, created on 2025-11-26 16:10:49
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vtiger/dashboards/KeyMetricsContents.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_692726899574d7_59591881',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6274d232424fb7cec4d551f4c1c591ee5005d3d2' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Vtiger/dashboards/KeyMetricsContents.tpl',
      1 => 1727649312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_692726899574d7_59591881 (Smarty_Internal_Template $_smarty_tpl) {
?><div><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['KEYMETRICS']->value, 'KEYMETRIC');
$_smarty_tpl->tpl_vars['KEYMETRIC']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['KEYMETRIC']->value) {
$_smarty_tpl->tpl_vars['KEYMETRIC']->do_else = false;
?><div style="padding-bottom:6px;"><span class="pull-right"><?php echo $_smarty_tpl->tpl_vars['KEYMETRIC']->value['count'];?>
</span><a href="?module=<?php echo $_smarty_tpl->tpl_vars['KEYMETRIC']->value['module'];?>
&view=List&viewname=<?php echo $_smarty_tpl->tpl_vars['KEYMETRIC']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['KEYMETRIC']->value['name'];?>
</a></div><?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></div>
<?php }
}
