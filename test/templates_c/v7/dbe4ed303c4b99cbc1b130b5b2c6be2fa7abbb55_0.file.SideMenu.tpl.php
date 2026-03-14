<?php
/* Smarty version 4.5.4, created on 2025-03-10 11:50:18
  from '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Mobile/simple/Vtiger/SideMenu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67ced1faa2a456_81360180',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dbe4ed303c4b99cbc1b130b5b2c6be2fa7abbb55' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Mobile/simple/Vtiger/SideMenu.tpl',
      1 => 1727629512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67ced1faa2a456_81360180 (Smarty_Internal_Template $_smarty_tpl) {
?>
<md-sidenav class="md-sidenav-left" md-component-id="left">
    <md-toolbar class="app-menu md-locked-open">
        <div class="user-details">
            <md-list-item class="md-1-line" style="margin:10px 0px">
            
            <img src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/images/default_1.png" class="md-avatar" alt="user">
            
                <div class="md-list-item-text">
                    <small>{{userinfo.first_name + " "}}{{userinfo.last_name}}</small>
                    <h5 style="margin: 0px;">{{userinfo.email}}</h5>
                </div>
            </md-list-item>
        </div>
        <div class="app-dropdown">
            <md-select ng-model="selectedApp" aria-label="app_menu">
                <md-option ng-repeat="app in apps" ng-value="app" ng-click="setSelectedApp(app)">{{app}}</md-option>
            </md-select>
        </div>
    </md-toolbar>

    <md-list class="sidenav-module-list">
        <md-list-item ng-click="navigationToggle(); loadList('Events');" md-ink-ripple class="md-1-line">
            <span style="font-size:14px;" class="vicon-calendar"></span> &nbsp; 
            <span class="vmodule-name">Events</span>
        </md-list-item>
        <md-list-item ng-click="navigationToggle(); loadList('Calendar');" md-ink-ripple class="md-1-line">
            <span style="font-size:14px;" class="vicon-calendar"></span> &nbsp; 
            <span class="vmodule-name">Tasks</span>
        </md-list-item>
        <md-divider></md-divider>
        <md-list-item ng-click="navigationToggle();loadList(module.name);" class="md-1-line" ng-click="module.label" ng-repeat="module in menus[selectedApp]">
            <span style="font-size: 14px;" class="vicon-{{module.name | lowercase | nospace}}"></span> &nbsp; 
            <span class="vmodule-name">{{module.label}}</span>
        </md-list-item>
    </md-list>
    <md-divider></md-divider>
    <md-list>
        <md-list-item md-ink-ripple class="md-1-line">
            <div class="md-list-item-text">
                <a href="#" class="logout-link" ng-click="logout();"><span class="mdi mdi-power"></span>&nbsp; Logout</a>
            </div>
        </md-list-item>
        <md-list-item class="md-1-line">
            <div class="md-list-item-text">
                &nbsp; 
            </div>
        </md-list-item>
    </md-list>
</md-sidenav>

<?php }
}
