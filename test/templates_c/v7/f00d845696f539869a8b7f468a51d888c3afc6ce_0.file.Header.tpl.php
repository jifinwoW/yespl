<?php
/* Smarty version 4.5.4, created on 2025-03-10 11:49:52
  from '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Mobile/simple/Header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67ced1e070af04_43576052',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f00d845696f539869a8b7f468a51d888c3afc6ce' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Mobile/simple/Header.tpl',
      1 => 1727629512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67ced1e070af04_43576052 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!doctype html>
<html ng-app="mobileapp">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Vtiger</title>
		<link rel="shortcut icon" href="data:image/x-icon;," type="image/x-icon"> 
		<link rel="stylesheet" href="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/angularjs/angular-material.min.css">
		<!--link rel="stylesheet" href="https://fonts.googleapis.com/css?family=RobotoDraft:300,400,500,700,400italic"-->

				<?php if ((isset($_smarty_tpl->tpl_vars['_styles']->value)) && $_smarty_tpl->tpl_vars['_styles']->value) {?>
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_styles']->value, '_style');
$_smarty_tpl->tpl_vars['_style']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_style']->value) {
$_smarty_tpl->tpl_vars['_style']->do_else = false;
?>
				<link type="text/css" rel="stylesheet" href="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['_style']->value;?>
">
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		<?php }?>
				<link type="text/css" rel="stylesheet" href="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/md-icons/css/materialdesignicons.min.css">
		<link type="text/css" rel="stylesheet" href="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/md-datepicker/angular-datepicker.min.css">
		<link type="text/css" rel="stylesheet" href="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/Vtiger-icons/style.css">
		<link type="text/css" rel="stylesheet" href="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/css/application.css">
		<link type="text/css" rel="stylesheet" href="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/css/style.css">
				<link type="text/css" rel="stylesheet" href="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/angularjs/angular-material-datetimepicker/css/material-datetimepicker.min.css">

		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/jquery/date.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/jquery/jquery2.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/angularjs/angular.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/angularjs/angular-touch.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/angularjs/angular-animate.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/angularjs/angular-aria.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/angularjs/angular-material.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/angularjs/angular-touch.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/jquery/purl.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/md-datepicker/angular-datepicker.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/md-datepicker/angular-clockpicker.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/Vtiger/js/application.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/Vtiger/js/Vtiger.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/Vtiger/js/Utils.js"><?php echo '</script'; ?>
>

				<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/moment/moment.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/moment/moment-timezone.min.js"><?php echo '</script'; ?>
>
				<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/libs/angularjs/angular-material-datetimepicker/js/angular-material-datetimepicker.js"><?php echo '</script'; ?>
>

				<?php if ($_smarty_tpl->tpl_vars['_scripts']->value) {?>
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_scripts']->value, '_script');
$_smarty_tpl->tpl_vars['_script']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['_script']->value) {
$_smarty_tpl->tpl_vars['_script']->do_else = false;
?>
				<?php echo '<script'; ?>
 type="text/javascript" src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['_script']->value;?>
"><?php echo '</script'; ?>
>
			<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		<?php }?>
		
	</head>
	
		<body ng-controller="VtigerBodyController" ng-init="init();" ng-cloak md-theme="{{dynamicTheme}}" md-theme-watch>
	
<?php }
}
