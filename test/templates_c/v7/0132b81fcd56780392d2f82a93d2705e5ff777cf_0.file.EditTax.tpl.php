<?php
/* Smarty version 4.5.4, created on 2026-01-26 08:22:04
  from '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Settings/Vtiger/EditTax.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_6977242c2bab53_48334592',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0132b81fcd56780392d2f82a93d2705e5ff777cf' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/staging/layouts/v7/modules/Settings/Vtiger/EditTax.tpl',
      1 => 1727649312,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6977242c2bab53_48334592 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_assignInScope('TAX_MODEL_EXISTS', true);
$_smarty_tpl->_assignInScope('TAX_ID', $_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getId());
$_smarty_tpl->_assignInScope('WIDTHTYPE', $_smarty_tpl->tpl_vars['CURRENT_USER_MODEL']->value->get('rowheight'));
if (empty($_smarty_tpl->tpl_vars['TAX_ID']->value)) {
$_smarty_tpl->_assignInScope('TAX_MODEL_EXISTS', false);
}?><div class="taxModalContainer modal-dialog modal-xs"><div class="modal-content"><form id="editTax" class="form-horizontal" method="POST"><?php if ($_smarty_tpl->tpl_vars['TAX_MODEL_EXISTS']->value) {
ob_start();
echo vtranslate('LBL_EDIT_TAX',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);
$_prefixVariable1 = ob_get_clean();
$_smarty_tpl->_assignInScope('TITLE', $_prefixVariable1);
} else {
ob_start();
echo vtranslate('LBL_ADD_NEW_TAX',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);
$_prefixVariable2 = ob_get_clean();
$_smarty_tpl->_assignInScope('TITLE', $_prefixVariable2);
}
$_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( "ModalHeader.tpl",$_smarty_tpl->tpl_vars['MODULE']->value )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('TITLE'=>$_smarty_tpl->tpl_vars['TITLE']->value), 0, true);
?><input type="hidden" name="taxid" value="<?php echo $_smarty_tpl->tpl_vars['TAX_ID']->value;?>
" /><input type="hidden" name="type" value="<?php echo $_smarty_tpl->tpl_vars['TAX_TYPE']->value;?>
" /><div class="modal-body" id="scrollContainer"><div class=""><div class="block nameBlock row"><div class="col-lg-1"></div><div class="col-lg-3"><label class="pull-right"><?php echo vtranslate('LBL_TAX_NAME',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
&nbsp;<span class="redColor">*</span></label></div><div class="col-lg-5"><input class="inputElement" type="text" name="taxlabel" placeholder="<?php echo vtranslate('LBL_ENTER_TAX_NAME',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
" value="<?php echo $_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getName();?>
" data-rule-required="true" data-prompt-position="bottomLeft" /></div><div class="col-lg-3"></div></div><div class="block statusBlock row"><div class="col-lg-1"></div><div class="col-lg-3"><label class="pull-right"><?php echo vtranslate('LBL_STATUS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></div><div class="col-lg-7"><input type="hidden" name="deleted" value="1" /><label><input type="checkbox" name="deleted" value="0" class="taxStatus" <?php if ($_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->isDeleted() == 0 || !$_smarty_tpl->tpl_vars['TAX_ID']->value) {?> checked <?php }?> /><span>&nbsp;&nbsp;<?php echo vtranslate('LBL_TAX_STATUS_DESC',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</span></label></div><div class="col-lg-1"></div></div><?php if ($_smarty_tpl->tpl_vars['TAX_MODEL_EXISTS']->value == false) {?><div class="block taxCalculationBlock row"><div class="col-lg-1"></div><div class="col-lg-3"><label class="pull-right"><?php echo vtranslate('LBL_TAX_CALCULATION',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></div><div class="col-lg-7"><label class="span radio-group" id="simple"><input type="radio" name="method" class="input-medium" <?php if ($_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTaxMethod() == 'Simple' || !$_smarty_tpl->tpl_vars['TAX_ID']->value) {?>checked<?php }?> value="Simple" />&nbsp;&nbsp;<span class="radio-label"><?php echo vtranslate('LBL_SIMPLE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</span></label>&nbsp;&nbsp;<label class="span radio-group" id="compound"><input type="radio" name="method" class="input-medium" <?php if ($_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTaxMethod() == 'Compound') {?>checked<?php }?> value="Compound" />&nbsp;&nbsp;<span class="radio-label"><?php echo vtranslate('LBL_COMPOUND',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</span></label>&nbsp;&nbsp;<?php if ($_smarty_tpl->tpl_vars['TAX_TYPE']->value != 1) {?><label class="span radio-group" id="deducted"><input type="radio" name="method" class="input-medium" <?php if ($_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTaxMethod() == 'Deducted') {?>checked<?php }?> value="Deducted" />&nbsp;&nbsp;<span class="radio-label"><?php echo vtranslate('LBL_DEDUCTED',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</span></label><?php }?></div><div class="col-lg-1"></div></div><?php } else { ?><input type="hidden" name="method" value="<?php echo $_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTaxMethod();?>
" /><?php }?><div class="block compoundOnContainer row <?php if ($_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTaxMethod() != 'Compound') {?>hide<?php }?>"><div class="col-lg-1"></div><div class="col-lg-3"><label class="pull-right"><?php echo vtranslate('LBL_COMPOUND_ON',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
&nbsp;<span class="redColor">*</span></label></div><div class="col-lg-5"><div class=""><?php $_smarty_tpl->_assignInScope('SELECTED_SIMPLE_TAXES', $_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTaxesOnCompound());?><select data-placeholder="<?php echo vtranslate('LBL_SELECT_SIMPLE_TAXES',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
" id="compoundOn" class="select2 inputEle" multiple="" name="compoundon" data-rule-required="true"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['SIMPLE_TAX_MODELS_LIST']->value, 'SIMPLE_TAX_MODEL', false, 'SIMPLE_TAX_ID');
$_smarty_tpl->tpl_vars['SIMPLE_TAX_MODEL']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['SIMPLE_TAX_ID']->value => $_smarty_tpl->tpl_vars['SIMPLE_TAX_MODEL']->value) {
$_smarty_tpl->tpl_vars['SIMPLE_TAX_MODEL']->do_else = false;
?><option value="<?php echo $_smarty_tpl->tpl_vars['SIMPLE_TAX_ID']->value;?>
" <?php if (!empty($_smarty_tpl->tpl_vars['SELECTED_SIMPLE_TAXES']->value) && in_array($_smarty_tpl->tpl_vars['SIMPLE_TAX_ID']->value,$_smarty_tpl->tpl_vars['SELECTED_SIMPLE_TAXES']->value)) {?>selected=""<?php }?>><?php echo $_smarty_tpl->tpl_vars['SIMPLE_TAX_MODEL']->value->getName();?>
 (<?php echo $_smarty_tpl->tpl_vars['SIMPLE_TAX_MODEL']->value->getTax();?>
%)</option><?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></select></div></div><div class="col-lg-3"></div></div><div class="block taxTypeContainer row <?php if ($_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTaxMethod() == 'Deducted') {?>hide<?php }?>"><div class="col-lg-1"></div><div class="col-lg-3"><label class="pull-right"><?php echo vtranslate('LBL_TAX_TYPE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</label></div><div class="col-lg-7"><label class="span radio-group" id="fixed"><input type="radio" name="taxType" class="input-medium" <?php if ($_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTaxType() == 'Fixed' || !$_smarty_tpl->tpl_vars['TAX_ID']->value) {?>checked<?php }?> value="Fixed" />&nbsp;&nbsp;<span class="radio-label"><?php echo vtranslate('LBL_FIXED',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</span></label>&nbsp;&nbsp;<label class="span radio-group" id="variable"><input type="radio" name="taxType" class="input-medium" <?php if ($_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTaxType() == 'Variable') {?>checked<?php }?> value="Variable" />&nbsp;&nbsp;<span class="radio-label"><?php echo vtranslate('LBL_VARIABLE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</span></label>&nbsp;&nbsp;</div><div class="col-lg-1"></div></div><div class="block taxValueContainer row <?php if ($_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTaxType() == 'Variable') {?>hide<?php }?>"><div class="col-lg-1"></div><div class="col-lg-3"><label class="pull-right"><?php echo vtranslate('LBL_TAX_VALUE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
&nbsp;<span class="redColor">*</span></label></div><div class="col-lg-5"><div class="input-group" style="min-height:30px;"><span class="input-group-addon">%</span><input class="inputElement" type="text" name="percentage" placeholder="<?php echo vtranslate('LBL_ENTER_TAX_VALUE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
" value="<?php echo $_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTax();?>
" data-rule-required="true" data-rule-inventory_percentage="true" /></div></div><div class="col-lg-3"></div></div><div class="control-group dedcutedTaxDesc <?php if ($_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTaxMethod() != 'Deducted') {?>hide<?php }?>"><div style="text-align:center;"><i class="fa fa-info-circle"></i> <?php echo vtranslate('LBL_DEDUCTED_TAX_DISC',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</div><br><br></div><div class="block regionsContainer row <?php if ($_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTaxType() != 'Variable') {?>hide<?php }?>" style="padding: 0px 40px;"><table class="table table-bordered regionsTable"><tr><th class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
" style="width:70%;"><strong><?php echo vtranslate('LBL_REGIONS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</strong></th><th class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
" style="text-align: center; width:30%;"><strong><?php echo vtranslate('LBL_TAX_VALUE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
&nbsp;(%)</strong></th></tr><tr><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
"><label><?php echo vtranslate('LBL_DEFAULT_VALUE',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
&nbsp;<span class="redColor">*</span></label></td><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
" style="text-align: center;"><input class="inputElement smallInputBox input-medium" type="text" name="defaultPercentage" value="<?php echo $_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getTax();?>
" data-rule-required="true" data-rule-inventory_percentage="true" /></td></tr><?php $_smarty_tpl->_assignInScope('i', 0);
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['TAX_RECORD_MODEL']->value->getRegionTaxes(), 'REGIONS_INFO', false, NULL, 'i', array (
));
$_smarty_tpl->tpl_vars['REGIONS_INFO']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['REGIONS_INFO']->value) {
$_smarty_tpl->tpl_vars['REGIONS_INFO']->do_else = false;
?><tr><td class="regionsList <?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
"><div class="deleteRow close" style="float:left;margin-top:2px;">×</div>&nbsp;&nbsp;<select id="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
" data-placeholder="<?php echo vtranslate('LBL_SELECT_REGIONS',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
" name="regions[<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
][list]" class="regions select2 inputElement" multiple="" data-rule-required="true" style="width: 90%;"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['TAX_REGIONS']->value, 'TAX_REGION_MODEL');
$_smarty_tpl->tpl_vars['TAX_REGION_MODEL']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['TAX_REGION_MODEL']->value) {
$_smarty_tpl->tpl_vars['TAX_REGION_MODEL']->do_else = false;
$_smarty_tpl->_assignInScope('TAX_REGION_ID', $_smarty_tpl->tpl_vars['TAX_REGION_MODEL']->value->getId());?><option value="<?php echo $_smarty_tpl->tpl_vars['TAX_REGION_ID']->value;?>
" <?php if (in_array($_smarty_tpl->tpl_vars['TAX_REGION_ID']->value,$_smarty_tpl->tpl_vars['REGIONS_INFO']->value['list'])) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['TAX_REGION_MODEL']->value->getName();?>
</option><?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></select></td><td class="<?php echo $_smarty_tpl->tpl_vars['WIDTHTYPE']->value;?>
" style="text-align: center;"><input class="inputElement" type="text" name="regions[<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
][value]" value="<?php echo $_smarty_tpl->tpl_vars['REGIONS_INFO']->value['value'];?>
" data-rule-required="true" data-rule-inventory_percentage="true" /></td></tr><?php $_smarty_tpl->_assignInScope('i', $_smarty_tpl->tpl_vars['i']->value+1);
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?><input type="hidden" class="regionsCount" value="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
" /></table><span class="addNewTaxBracket"><a href="#"><u><?php echo vtranslate('LBL_ADD_TAX_BRACKET',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</u></a><select class="taxRegionElements hide"><?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['TAX_REGIONS']->value, 'TAX_REGION_MODEL');
$_smarty_tpl->tpl_vars['TAX_REGION_MODEL']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['TAX_REGION_MODEL']->value) {
$_smarty_tpl->tpl_vars['TAX_REGION_MODEL']->do_else = false;
?><option value="<?php echo $_smarty_tpl->tpl_vars['TAX_REGION_MODEL']->value->getId();?>
"><?php echo $_smarty_tpl->tpl_vars['TAX_REGION_MODEL']->value->getName();?>
</option><?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></select></span><br><br><div><i class="fa fa-info-circle"></i> <?php echo vtranslate('LBL_TAX_BRACKETS_DESC',$_smarty_tpl->tpl_vars['QUALIFIED_MODULE']->value);?>
</div></div></div></div><?php $_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( 'ModalFooter.tpl','Vtiger' )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?></form></div></div>
<?php }
}
