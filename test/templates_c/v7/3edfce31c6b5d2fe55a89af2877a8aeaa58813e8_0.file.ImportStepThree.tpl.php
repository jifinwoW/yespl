<?php
/* Smarty version 4.5.4, created on 2026-01-26 07:37:25
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Import/ImportStepThree.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_697719b5e3b517_27319782',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3edfce31c6b5d2fe55a89af2877a8aeaa58813e8' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Import/ImportStepThree.tpl',
      1 => 1739207400,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_697719b5e3b517_27319782 (Smarty_Internal_Template $_smarty_tpl) {
?>


<span>
    <h4><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtranslate' ][ 0 ], array( 'LBL_IMPORT_MAP_FIELDS',$_smarty_tpl->tpl_vars['MODULE']->value ));?>
</h4>
</span>
<hr>
<div id="savedMapsContainer"><?php $_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( "Import_Saved_Maps.tpl",'Import' )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?></div>
<div><?php $_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( "Import_Mapping.tpl",'Import' )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?></div>
<div class="form-inline" style="padding-bottom: 10%;">
    <input type="checkbox" name="save_map" id="save_map">&nbsp;&nbsp;<label for="save_map"><?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtranslate' ][ 0 ], array( 'LBL_SAVE_AS_CUSTOM_MAPPING',$_smarty_tpl->tpl_vars['MODULE']->value ));?>
</label>
    &nbsp;&nbsp;<input type="text" name="save_map_as" id="save_map_as" class = "form-control">
</div>
<?php if (!(isset($_smarty_tpl->tpl_vars['IMPORTABLE_FIELDS']->value)) || !$_smarty_tpl->tpl_vars['IMPORTABLE_FIELDS']->value) {?>
	<?php $_smarty_tpl->_assignInScope('IMPORTABLE_FIELDS', $_smarty_tpl->tpl_vars['AVAILABLE_FIELDS']->value);
}
$_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( "Import_Default_Values_Widget.tpl",'Import' )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('IMPORTABLE_FIELDS'=>$_smarty_tpl->tpl_vars['IMPORTABLE_FIELDS']->value), 0, true);
}
}
