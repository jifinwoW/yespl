<?php

class ITS4YouItemsWidget_Currency_UIType extends Vtiger_Currency_UIType
{
    public static function convertToUserFormat($value)
    {
        global $current_user;

        $field = new ITS4YouItemsWidget_CurrencyField_Helper($value);

        $value = $field->_formatCurrencyValue($value);

        $value = $field->currencyDecimalFormat($value, $current_user);

        return $field->getDisplayValue($current_user);
    }

}