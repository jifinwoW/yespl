<?php
/* Smarty version 4.5.4, created on 2025-03-10 11:50:18
  from '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Mobile/simple/Vtiger/Toolbar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67ced1faa27bc9_44805973',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '682884f0e39ec432befd9908004277cc77b7e1fa' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Mobile/simple/Vtiger/Toolbar.tpl',
      1 => 1727629512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67ced1faa27bc9_44805973 (Smarty_Internal_Template $_smarty_tpl) {
?>
    <header md-page-header fixed-top>
        <md-toolbar>
            <div class="md-toolbar-tools actionbar">
                <md-button ng-click="navigationToggle()" class="md-icon-button" aria-label="side-menu-open">
                    <i class="mdi mdi-menu actionbar-icon"></i>
                </md-button>
                <h2 flex class="toolbar-title">{{pageTitle}}</h2>               
            </div>
        </md-toolbar>
    </header>
 <?php }
}
