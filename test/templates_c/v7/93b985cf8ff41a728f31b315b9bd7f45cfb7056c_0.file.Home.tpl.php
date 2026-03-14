<?php
/* Smarty version 4.5.4, created on 2025-03-10 11:50:04
  from '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Mobile/simple/Vtiger/Home.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67ced1ec313c57_50351681',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '93b985cf8ff41a728f31b315b9bd7f45cfb7056c' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Mobile/simple/Vtiger/Home.tpl',
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
function content_67ced1ec313c57_50351681 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('scripts'=>$_smarty_tpl->tpl_vars['_scripts']->value), 0, false);
?>

    <md-toolbar layout="row">
        <div class="md-toolbar-tools actionbar">
            <md-button ng-click="navigationToggle()" class="md-icon-button" aria-label="side-menu-open">
                <i class="mdi mdi-menu actionbar-icon"></i>
            </md-button>
            <h2>{{pageTitle}}</h2>
            <span flex></span>
            <md-button class="md-icon-button" aria-label="global-search">
                <i class="mdi mdi-magnify  actionbar-icon"></i>
            </md-button>
        </div>
    </md-toolbar>
    <section layout="row" flex>
        <md-sidenav class="md-sidenav-left" md-component-id="left">
            <md-toolbar class="app-menu md-locked-open">
                <!--div class="md-toolbar-tools">
                    <md-button ng-click="navigationToggle()" class="md-icon-button" aria-label="side-menu-close">
                        <i class="mdi mdi-arrow-left actionbar-icon"></i>
                    </md-button>
                </div-->
                <div class="user-details">
                    <md-list-item class="md-1-line">
                    
                    <img src="../../<?php echo $_smarty_tpl->tpl_vars['TEMPLATE_WEBPATH']->value;?>
/resources/images/default_1.png" class="md-avatar" alt="user">
                    
                        <div class="md-list-item-text">
                            <div>{{userinfo.first_name + " "}}{{userinfo.last_name}}</div>
							<h5 style="margin: 0px;">{{userinfo.email}}</h5>
                        </div>
                    </md-list-item>
                </div>
                <md-input-container class="app-dropdown">
                    <md-select ng-model="selectedApp" aria-label="app_menu">
                        <md-option ng-repeat="app in apps" ng-value="app">{{app}}</md-option>
                    </md-select>
                </md-input-container>
            </md-toolbar>

            <md-list class="sidenav-module-list">
                <md-list-item ng-click="navigationToggle();setPageTitle('Dashboard')" class="md-1-line">
                    <span class="vicon-grid"></span> &nbsp; 
                    <span class="vmodule-name">Dashboard</span>
                </md-list-item>
                <md-list-item ng-click="navigationToggle();loadList(module.name)" class="md-1-line" ng-repeat="module in menus[selectedApp]">
                    <span class="vicon-{{module.name | lowercase | nospace}}"></span> &nbsp; 
                    <span class="vmodule-name">{{module.label}}</span>
                </md-list-item>
            </md-list>
            <md-divider ></md-divider>
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

        <div flex layout-padding class="list-content">
            <!-- Home Content -->
        </div>
    </section>

</div>
</section>

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
