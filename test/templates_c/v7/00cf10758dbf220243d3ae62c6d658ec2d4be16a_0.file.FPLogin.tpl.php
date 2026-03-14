<?php
/* Smarty version 4.5.4, created on 2025-10-02 11:22:38
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Users/FPLogin.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_68de607e11cfb1_29683092',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '00cf10758dbf220243d3ae62c6d658ec2d4be16a' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Users/FPLogin.tpl',
      1 => 1727649312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68de607e11cfb1_29683092 (Smarty_Internal_Template $_smarty_tpl) {
?>

<?php if ($_smarty_tpl->tpl_vars['ERROR']->value) {?>
	<?php echo $_smarty_tpl->tpl_vars['MESSAGE']->value;?>

<?php } else { ?>
	<h4>Loading .... </h4>
	<form class="form-horizontal" name="login" id="login" method="post" action="../../../index.php?module=Users&action=Login">
		<input type=hidden name="username" value="<?php echo $_smarty_tpl->tpl_vars['USERNAME']->value;?>
" >
		<input type=hidden name="password" value="<?php echo $_smarty_tpl->tpl_vars['PASSWORD']->value;?>
" >
	</form>
	<?php echo '<script'; ?>
 type="text/javascript">
		function autoLogin () {
			var form = document.getElementById("login");
			form.submit();
		}
		window.onload = autoLogin;
	<?php echo '</script'; ?>
>
<?php }
}
}
