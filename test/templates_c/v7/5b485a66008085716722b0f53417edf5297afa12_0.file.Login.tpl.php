<?php
/* Smarty version 4.5.4, created on 2025-03-10 11:49:52
  from '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Mobile/simple/Users/Login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67ced1e07018c8_77607772',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5b485a66008085716722b0f53417edf5297afa12' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Mobile/simple/Users/Login.tpl',
      1 => 1727629512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../Header.tpl' => 1,
    'file:../Footer.tpl' => 1,
  ),
),false)) {
function content_67ced1e07018c8_77607772 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('_scripts', call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'explode' ][ 0 ], array( ',','Users/Users.js' )));?>

<?php $_smarty_tpl->_subTemplateRender("file:../Header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('scripts'=>$_smarty_tpl->tpl_vars['_scripts']->value), 0, false);
?>


    <section ng-controller="UsersLoginController" layout="column" id="page">
        <md-content class="login-background">
            <div class="logo-container">
            
            <img src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/images/vtiger_logo.svg" alt="Vtiger Logo"/>
            
            </div>
            <form name="loginForm" ng-submit="login()" class="login-form" ng-validate>
                <md-input-container  md-no-float class="md-hue-1">
                    <label for="username" >User name</label>
                    <input type="text" autoFilled="true" id="user-name" name="username" ng-model="auth.username" ng-required="true">
                    <div class="form-errors" ng-message="required" ng-show="loginForm.username.$invalid && loginForm.username.$touched">User Name required!</div>
                </md-input-container>
                <md-input-container class="md-hue-1">
                    <label for="password">Password</label>
                    <input type="password" autoFilled="true" id="password" name="password" ng-model="auth.password" ng-required="true">
                    <div class="form-errors" ng-message="required" ng-show="loginForm.password.$invalid && loginForm.password.$touched">Password required!</div>
                </md-input-container>
                <!--div ng-messages="loginForm.username.$error" class="form-errors"-->
                    
                    
                <!--/div-->
                <md-input-container>
                    <md-button class="md-raised" type="submit" value="Login">
                        Login
                    </md-button>
                </md-input-container>

                <a href="#" class="md-primary md-hue-1">Forgot password</a>
            </form>
        </md-content>
    </section>
	

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
