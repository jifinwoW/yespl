{*<!--
/* * *******************************************************************************
* The content of this file is subject to the ITS4YouItemsWidget license.
* ("License"); You may not use this file except in compliance with the License
* The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
* Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
* All Rights Reserved.
* ****************************************************************************** */
-->*}

{strip}
    {if !empty($smarty.request.record) AND class_exists('ITSGPSForce_GpsRecordData_Helper')}
        {assign var=GPS_PRODUCTS_DATA_BY_SO value=ITSGPSForce_GpsRecordData_Helper::getSORecordGpsForceProductsData($smarty.request.record)}
    {/if}
    <div>
        {* Module Summary View*}
        <div class="summaryView">
            <div class="summaryViewHeader">
                <h4 class="display-inline-block">{vtranslate('LBL_ITEM_DETAILS', $MODULE_NAME)}</h4>
            </div>
            <div class="summaryViewFields">
                {$MODULE_SUMMARY}
            </div>

            <table class="summary-table no-border">
                <tbody>

                {* HEADERS START *}
                <tr class="summaryViewHeader">
                    <td class="fieldLabel" width="57%" align="left">
                        <label class="muted textOverflowEllipsis">
                            {vtranslate('LBL_ITEM_NAME', $MODULE_NAME)}
                        </label>
                    </td>
                    <td class="fieldLabel" width="20%" align="right" style="padding-right: 0;">
                        <label class="muted textOverflowEllipsis">
                            {vtranslate('LBL_QTY', $MODULE_NAME)}
                        </label>
                    </td>
                    <td class="fieldLabel" width="23%" align="right" style="padding-right: 0;">
                        <label class="muted textOverflowEllipsis">
                            {vtranslate('LBL_NET_PRICE', $MODULE_NAME)}
                        </label>
                    </td>
                </tr>
                {* HEADERS END *}

                {* ITEM ROWS START *}
                {assign var=FINAL_DETAILS value=$PRODUCTS.1.final_details}
                {foreach $PRODUCTS as $row_no => $data}
                    {assign var="productName" value="productName"|cat:$row_no}
                    {assign var="qty" value="qty"|cat:$row_no}
                    {assign var="netPrice" value="netPrice"|cat:$row_no}
                    {if !empty($data["productName$row_no"])}
                        <tr class="summaryViewEntries">
                            <td class="fieldValue" style="white-space: normal;">
                                <span style="display: inline-block;">
                                    <div style="white-space: normal;">
                                        {if $data["productDeleted$row_no"]}
                                            {$data["productName$row_no"]}
                                        {else}
                                            <h5><a class="fieldValue" href="index.php?module={$data["entityType$row_no"]}&view=Detail&record={$data["hdnProductId$row_no"]}" target="_blank">{$data["productName$row_no"]}</a>
                                            {if class_exists('ITS4YouImport_GpsRecordData_Helper') && 'SalesOrder' eq $MODULE_NAME}
                                                {if ITSGPSForce_GpsProductCategories_Helper::isGpsProduct($data["hdnProductId$row_no"])
                                                && $data["qty$row_no"] > $GPS_PRODUCTS_DATA_BY_SO[$data["hdnProductId$row_no"]]
                                                && 'Cancelled' neq $RECORD->get('sostatus')}
                                                    &nbsp;<a href="javascript:;"><span class="tab-icon" data-gps-name="set-gps" data-product-id="{$data["hdnProductId$row_no"]}"><img src="layouts/v7/modules/ITSGPSForce/ITSGPSForce.png" title="{vtranslate('ITSGPSForce', 'ITSGPSForce')}"></span></a>
                                                    &nbsp;{if !empty($GPS_PRODUCTS_DATA_BY_SO[$data["hdnProductId$row_no"]])}{$GPS_PRODUCTS_DATA_BY_SO[$data["hdnProductId$row_no"]]}{else}0{/if}/{$data["qty$row_no"]}
                                                {/if}
                                            {/if}
                                            {if class_exists('ITSONEForce_OneProductCategories_Helper')}
                                                {if ITSONEForce_OneProductCategories_Helper::isSimProduct($LINE_ITEM_DETAIL["hdnProductId$INDEX"])
                                                && $LINE_ITEM_DETAIL["qty$INDEX"] > $ONE_PRODUCTS_DATA_BY_SO[$LINE_ITEM_DETAIL["hdnProductId$INDEX"]]
                                                && 'Cancelled' neq $RECORD->get('sostatus')}
                                                    &nbsp;
                                                    <a href="javascript:;"><span class="tab-icon" data-one-name="set-one" data-product-id="{$LINE_ITEM_DETAIL["hdnProductId$INDEX"]}"><img src="layouts/v7/modules/ITSONEForce/ITSONEForce.png" title="{vtranslate('ITSONEForce', 'ITSONEForce')}" height="18px"></span></a>
                                                    &nbsp;
                                                    {if !empty($ONE_PRODUCTS_DATA_BY_SO[$LINE_ITEM_DETAIL["hdnProductId$INDEX"]])}{$ONE_PRODUCTS_DATA_BY_SO[$LINE_ITEM_DETAIL["hdnProductId$INDEX"]]}{else}0{/if}/{$LINE_ITEM_DETAIL["qty$INDEX"]}
                                                {/if}
                                            {/if}
                                            </h5>
                                        {/if}
                                    </div>
                                    {if $data["productDeleted$row_no"]}
                                        <div class="redColor deletedItem">
                                            {if empty($data["productName$row_no"])}
                                                {vtranslate('LBL_THIS_LINE_ITEM_IS_DELETED_FROM_THE_SYSTEM_PLEASE_REMOVE_THIS_LINE_ITEM',$MODULE)}
                                            {else}
                                                {vtranslate('LBL_THIS',$MODULE)} {$data["entityType$row_no"]} {vtranslate('LBL_IS_DELETED_FROM_THE_SYSTEM_PLEASE_REMOVE_OR_REPLACE_THIS_ITEM',$MODULE)}
                                            {/if}
                                        </div>
                                    {/if}
                                    <div>
                                        {$data["subprod_names$row_no"]}
                                    </div>
                                </span>
                            </td>
                            <td class="fieldValue">
                                <div align="right">
                                <span style="display: inline-block;">
                                    {decimalFormat($data.$qty)}
                                </span>
                                </div>
                            </td>
                            <td class="fieldValue">
                                <div align="right">
                                <span style="display: inline-block;">
                                    {ITS4YouItemsWidget_Currency_UIType::convertToUserFormat($data.$netPrice, null, false)}
                                </span>
                                </div>
                            </td>
                        </tr>
                    {else}
                        <tr class="summaryViewEntries">
                            <td class="fieldValue" style="white-space: normal;">
                                {vtranslate('LBL_NO_RECORDS_FOUND', $MODULE_NAME)}
                            </td>
                        </tr>
                    {/if}
                {foreachelse}
                    <tr class="summaryViewEntries">
                        <td class="fieldValue" style="white-space: normal;">
                            {vtranslate('LBL_NO_RECORDS_FOUND', $MODULE_NAME)}
                        </td>
                    </tr>
                {/foreach}
                {* ITEM ROWS END *}

                {* TOTAL WITHOUT VAT IF NEEDED START *}
                {if $FINAL_DETAILS.taxtype eq 'group' && 0 < $FINAL_DETAILS['tax_totalamount']
                || '0.00' neq $FINAL_DETAILS['discountTotal_final']}
                    <tr class="summaryViewEntries">
                        <td class="fieldValue">
                            &nbsp;
                        </td>
                        <td class="fieldValue">
                            <div align="right">
                                <h6>
                                    {vtranslate('LBL_PRE_TAX_TOTAL',$MODULE_NAME)}
                                </h6>
                            </div>
                        </td>
                        <td class="fieldValue">
                            <div align="right">
                                <span style="display: inline-block;margin-bottom: 5px;">
                                    {ITS4YouItemsWidget_Currency_UIType::convertToUserFormat($FINAL_DETAILS['preTaxTotal'], null, false)}
                                </span>
                            </div>
                        </td>
                    </tr>
                {/if}
                {* TOTAL WITHOUT VAT IF NEEDED END *}

                {* DISCOUNT TOTAL START *}
                {if '0.00' neq $FINAL_DETAILS['discountTotal_final']}
                    <tr class="summaryViewEntries">
                        <td class="fieldValue">
                            &nbsp;
                        </td>
                        <td class="fieldValue">
                            <div align="right">
                            <span style="display: inline-block;margin-bottom: 5px;">
                                {vtranslate('LBL_OVERALL_DISCOUNT',$MODULE_NAME)}
                            </span>
                            </div>
                        </td>
                        <td class="fieldValue">
                            <div align="right">
                            <h6>
                                (-)&nbsp;{ITS4YouItemsWidget_Currency_UIType::convertToUserFormat($FINAL_DETAILS['discountTotal_final'], null, false)}
                            </h6>
                            </div>
                        </td>
                    </tr>
                {/if}
                {* DISCOUNT TOTAL END *}

                {* TAX TOTAL START *}
                {if $FINAL_DETAILS.taxtype eq 'group' && 0 < $FINAL_DETAILS['tax_totalamount']}
                    <tr class="summaryViewEntries">
                        <td class="fieldValue">
                            &nbsp;
                        </td>
                        <td class="fieldValue">
                            <div align="right">
                                <h6>
                                    {assign var=GROUP_TAX_INFO value="{vtranslate('LBL_TOTAL_AFTER_DISCOUNT',$MODULE_NAME)} = {ITS4YouItemsWidget_Currency_UIType::convertToUserFormat($FINAL_DETAILS['totalAfterDiscount'])}<br /><br />{foreach item=tax_details from=$FINAL_DETAILS['taxes']}{$tax_details['taxlabel']} : \t{ITS4YouItemsWidget_Currency_UIType::convertToUserFormat($tax_details['percentage'])}% {vtranslate('LBL_OF',$MODULE_NAME)} {if $tax_details['method'] eq 'Compound'}({/if}{ITS4YouItemsWidget_Currency_UIType::convertToUserFormat($FINAL_DETAILS['totalAfterDiscount'])}{if $tax_details['method'] eq 'Compound'}{foreach item=COMPOUND_TAX_ID from=$tax_details['compoundon']}{if $FINAL_DETAILS['taxes'][$COMPOUND_TAX_ID]['taxlabel']} + {$FINAL_DETAILS['taxes'][$COMPOUND_TAX_ID]['taxlabel']}{/if}{/foreach}){/if} = {ITS4YouItemsWidget_Currency_UIType::convertToUserFormat($tax_details['amount'])}<br />{/foreach}<br />{vtranslate('LBL_TOTAL_TAX_AMOUNT',$MODULE_NAME)} = {ITS4YouItemsWidget_Currency_UIType::convertToUserFormat($FINAL_DETAILS['tax_totalamount'])}"}
                                    (+)&nbsp;<strong><a class="inventoryLineItemDetails" tabindex="0" role="tooltip" href="javascript:void(0)" id="finalTax" data-trigger ="focus" data-placement ="left" title = "{vtranslate('LBL_TAX',$MODULE_NAME)}" data-toggle ="popover" data-content="{$GROUP_TAX_INFO}">{vtranslate('LBL_TAX',$MODULE_NAME)}</a></strong>
                                </h6>
                            </div>
                        </td>
                        <td class="fieldValue">
                            <div align="right">
                                <span style="display: inline-block;margin-bottom: 5px;">
                                    (+)&nbsp;{ITS4YouItemsWidget_Currency_UIType::convertToUserFormat($FINAL_DETAILS['tax_totalamount'], null, false)}
                                </span>
                            </div>
                        </td>
                    </tr>
                {/if}
                {* TAX TOTAL END *}

                {* GRANT TOTAL START *}
                <tr class="summaryViewEntries">
                    <td class="fieldValue">
                        &nbsp;
                    </td>
                    <td class="fieldValue">
                        <div align="right">
                        <span style="display: inline-block;margin-bottom: 5px;">
                            <strong>{vtranslate('LBL_GRAND_TOTAL',$MODULE_NAME)}</strong>
                        </span>
                        </div>
                    </td>
                    <td class="fieldValue">
                        <div align="right">
                        <span style="display: inline-block;margin-bottom: 5px;">
                            {ITS4YouItemsWidget_Currency_UIType::convertToUserFormat($FINAL_DETAILS['grandTotal'], null, false)}
                        </span>
                        </div>
                    </td>
                </tr>
                {* GRANT TOTAL END *}

                </tbody>
            </table>
        </div>
    </div>
{/strip}
