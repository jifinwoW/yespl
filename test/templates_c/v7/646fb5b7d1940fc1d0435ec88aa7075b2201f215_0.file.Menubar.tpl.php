<?php
/* Smarty version 4.5.4, created on 2025-04-07 13:35:23
  from '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Documents/partials/Menubar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67f3d49b0fdc51_05070621',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '646fb5b7d1940fc1d0435ec88aa7075b2201f215' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Documents/partials/Menubar.tpl',
      1 => 1727629512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_67f3d49b0fdc51_05070621 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['REQ']->value->get('view') == 'Detail') {?>
<div id="modules-menu" class="modules-menu">    
    <ul>
        <li class="active">
            <a href="<?php echo $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getListViewUrl();?>
">
				<?php echo $_smarty_tpl->tpl_vars['MODULE_MODEL']->value->getModuleIcon();?>

                <span><?php echo $_smarty_tpl->tpl_vars['MODULE']->value;?>
</span>
            </a>
        </li>
    </ul>
</div>
<?php }
}
}
