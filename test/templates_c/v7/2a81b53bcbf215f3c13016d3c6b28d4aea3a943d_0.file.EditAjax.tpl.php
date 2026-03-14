<?php
/* Smarty version 4.5.4, created on 2026-01-17 23:12:50
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Settings/Currency/EditAjax.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_696c1772c3c316_52115141',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2a81b53bcbf215f3c13016d3c6b28d4aea3a943d' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Settings/Currency/EditAjax.tpl',
      1 => 1727649312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_696c1772c3c316_52115141 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('CURRENCY_MODEL_EXISTS', true);
$_smarty_tpl->_assignInScope('CURRENCY_ID', $_smarty_tpl->tpl_vars['RECORD_MODEL']->value->getId());
if (empty($_smarty_tpl->tpl_vars['CURRENCY_ID']->value)) {
$_smarty_tpl->_assignInScope('CURRENCY_MODEL_EXISTS', false);
}?><div class="currencyModalContainer modal-dialog modelContainer"><?php if ($_smarty_tpl->tpl_vars['CURRENCY_MODEL_EXISTS']->value) {
ob_start();
echo vtranslate('LBL_EDIT_CURRENCY',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);
$_prefixVariable1 = ob_get_clean();
$_smarty_tpl->_assignInScope('HEADER_TITLE', $_prefixVariable1);
} else {
ob_start();
echo vtranslate('LBL_ADD_NEW_CURRENCY',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);
$_prefixVariable2 = ob_get_clean();
$_smarty_tpl->_assignInScope('HEADER_TITLE', $_prefixVariable2);
}
$_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( "ModalHeader.tpl",$_smarty_tpl->tpl_vars['MODULE']->value )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('TITLE'=>$_smarty_tpl->tpl_vars['HEADER_TITLE']->value), 0, true);
?><div class="modal-content"><form id="editCurrency" class="form-horizontal" method="POST"><input type="hidden" name="record" value="<?php echo $_smarty_tpl->tpl_vars['CURRENCY_ID']->value;?>
" /><div class="modal-body"><div class="row-fluid"><div class="form-group"><label class="control-label fieldLabel col-sm-5"><?php echo vtranslate('LBL_CURRENCY_NAME',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
&nbsp;<span class="redColor">*</span></label><div class="controls fieldValue col-xs-6"><select class="select2 inputElement" name="currency_name"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ALL_CURRENCIES']->value, 'CURRENCY_MODEL', false, 'CURRENCY_ID', 'currencyIterator', array (
  'first' => true,
  'index' => true,
));
$_smarty_tpl->tpl_vars['CURRENCY_MODEL']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['CURRENCY_ID']->value => $_smarty_tpl->tpl_vars['CURRENCY_MODEL']->value) {
$_smarty_tpl->tpl_vars['CURRENCY_MODEL']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_currencyIterator']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_currencyIterator']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_currencyIterator']->value['index'];
if (!$_smarty_tpl->tpl_vars['CURRENCY_MODEL_EXISTS']->value && (isset($_smarty_tpl->tpl_vars['__smarty_foreach_currencyIterator']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_currencyIterator']->value['first'] : null)) {
$_smarty_tpl->_assignInScope('RECORD_MODEL', $_smarty_tpl->tpl_vars['CURRENCY_MODEL']->value);
}?><option value="<?php echo $_smarty_tpl->tpl_vars['CURRENCY_MODEL']->value->get('currency_name');?>
" data-code="<?php echo $_smarty_tpl->tpl_vars['CURRENCY_MODEL']->value->get('currency_code');?>
"data-symbol="<?php echo $_smarty_tpl->tpl_vars['CURRENCY_MODEL']->value->get('currency_symbol');?>
" <?php if ($_smarty_tpl->tpl_vars['RECORD_MODEL']->value->get('currency_name') == $_smarty_tpl->tpl_vars['CURRENCY_MODEL']->value->get('currency_name')) {?> selected <?php }?>><?php echo vtranslate($_smarty_tpl->tpl_vars['CURRENCY_MODEL']->value->get('currency_name'),$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
&nbsp;(<?php echo $_smarty_tpl->tpl_vars['CURRENCY_MODEL']->value->get('currency_symbol');?>
)</option><?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></select></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-5"><?php echo vtranslate('LBL_CURRENCY_CODE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
&nbsp;<span class="redColor">*</span></label><div class="controls fieldValue col-xs-6"><input type="text" class="inputElement bgColor cursorPointerNotAllowed" name="currency_code" readonly value="<?php echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value->get('currency_code');?>
" data-rule-required = "true" /></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-5"><?php echo vtranslate('LBL_CURRENCY_SYMBOL',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
&nbsp;<span class="redColor">*</span></label><div class="controls fieldValue col-xs-6"><input type="text" class="inputElement bgColor cursorPointerNotAllowed" name="currency_symbol" readonly value="<?php echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value->get('currency_symbol');?>
" data-rule-required = "true" /></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-5"><?php echo vtranslate('LBL_CONVERSION_RATE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
&nbsp;<span class="redColor">*</span></label><div class="controls fieldValue col-xs-6"><input type="text" class="inputElement" name="conversion_rate" data-rule-required = "true" data-rule-positive ="true" data-rule-greater_than_zero = "true" placeholder="<?php echo vtranslate('LBL_ENTER_CONVERSION_RATE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
"value="<?php echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value->get('conversion_rate');?>
"/><br><span class="muted">(<?php echo vtranslate('LBL_BASE_CURRENCY',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
 - <?php echo $_smarty_tpl->tpl_vars['BASE_CURRENCY_MODEL']->value->get('currency_name');?>
)</span></div></div><div class="form-group"><label class="control-label fieldLabel col-sm-5"><?php echo vtranslate('LBL_STATUS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label><div class="controls fieldValue col-xs-6"><label class="checkbox"><input type="hidden" name="currency_status" value="Inactive" /><input type="checkbox" name="currency_status" value="Active" class="currencyStatus alignBottom"<?php if (!$_smarty_tpl->tpl_vars['CURRENCY_MODEL_EXISTS']->value) {?> checked <?php } else {
echo $_smarty_tpl->tpl_vars['RECORD_MODEL']->value->get('currency_status');
if ($_smarty_tpl->tpl_vars['RECORD_MODEL']->value->get('currency_status') == 'Active') {?> checked <?php }
}?> /><span>&nbsp;<?php echo vtranslate('LBL_CURRENCY_STATUS_DESC',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</span></label></div></div><div class="control-group transferCurrency hide"><label class="muted control-label"><?php echo vtranslate('LBL_TRANSFER_CURRENCY',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
&nbsp;<?php echo vtranslate('LBL_TO',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label>&nbsp;<span class="redColor">*</span><div class="controls row-fluid"><select class="select2 span6" name="transform_to_id"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['OTHER_EXISTING_CURRENCIES']->value, 'CURRENCY_MODEL', false, 'CURRENCY_ID');
$_smarty_tpl->tpl_vars['CURRENCY_MODEL']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['CURRENCY_ID']->value => $_smarty_tpl->tpl_vars['CURRENCY_MODEL']->value) {
$_smarty_tpl->tpl_vars['CURRENCY_MODEL']->do_else = false;
?><option value="<?php echo $_smarty_tpl->tpl_vars['CURRENCY_ID']->value;?>
"><?php echo vtranslate($_smarty_tpl->tpl_vars['CURRENCY_MODEL']->value->get('currency_name'),$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</option><?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></select></div></div></div></div><?php $_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( 'ModalFooter.tpl','Vtiger' )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?></form></div></div>
<?php }
}
