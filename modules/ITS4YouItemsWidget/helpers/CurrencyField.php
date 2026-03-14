<?php

class ITS4YouItemsWidget_CurrencyField_Helper extends CurrencyField
{

    protected $CURRENCY_PATTERN_PLAIN = '123456789';
    protected $CURRENCY_PATTERN_SINGLE_GROUPING = '123456,789';
    protected $CURRENCY_PATTERN_THOUSAND_GROUPING = '123,456,789';
    protected $CURRENCY_PATTERN_MIXED_GROUPING = '12,34,56,789';


    /**
     * Function that converts the Number into Users Currency
     * @param Users $user
     * @param Boolean $skipConversion
     * @return Formatted Currency
     */
    public function getDisplayValue($user=null, $skipConversion=false, $skipFormatting=false) {
        global $current_user;
        if(empty($user)) {
            $user = $current_user;
        }
        $this->initialize($user);

        $value = $this->value;

        if($skipFormatting == false) {
            $value = $this->_formatCurrencyValue($value);
        }
        return $this->currencyDecimalFormat($value, $user);
    }

    /**
     * Function that formats the Number based on the User configured Pattern, Currency separator and Decimal separator
     *
     * @param Number $value
     *
     * @return Formatted Currency
     */
    public function _formatCurrencyValue($value)
    {
        if (empty($value)) {
            $value = 0;
        }
        $currencyPattern = $this->currencyFormat;
        $currencySeparator = $this->currencySeparator;
        $decimalSeparator = $this->decimalSeparator;
        $currencyDecimalPlaces = $this->numberOfDecimal;
        $value = number_format($value, $currencyDecimalPlaces, '.', '');
        if (empty($currencySeparator)) {
            $currencySeparator = ' ';
        }
        if (empty($decimalSeparator)) {
            $decimalSeparator = ' ';
        }

        if ($currencyPattern == $this->CURRENCY_PATTERN_PLAIN) {
            // Replace '.' with Decimal Separator
            $number = str_replace('.', $decimalSeparator, $value);

            return $number;
        }
        if ($currencyPattern == $this->CURRENCY_PATTERN_SINGLE_GROUPING) {
            // Separate the numeric and decimal parts
            $numericParts = explode('.', $value);
            $wholeNumber = $numericParts[0];
            // First part of the number which remains intact
            if (strlen($wholeNumber) > 3) {
                $wholeNumberFirstPart = substr($wholeNumber, 0, strlen($wholeNumber) - 3);
            }
            // Second Part of the number (last 3 digits) which should be separated from the First part using Currency Separator
            $wholeNumberLastPart = substr($wholeNumber, -3);
            // Re-create the whole number with user's configured currency separator
            if (!empty($wholeNumberFirstPart)) {
                $numericParts[0] = $wholeNumberFirstPart . $currencySeparator . $wholeNumberLastPart;
            } else {
                $numericParts[0] = $wholeNumberLastPart;
            }
            // Re-create the currency value combining the whole number and the decimal part using Decimal separator
            $number = implode($decimalSeparator, $numericParts);

            return $number;
        }
        if ($currencyPattern == $this->CURRENCY_PATTERN_THOUSAND_GROUPING) {
            $negativeNumber = false;
            if ($value < 0) {
                $negativeNumber = true;
            }

            // Separate the numeric and decimal parts
            $numericParts = explode('.', $value);
            $wholeNumber = $numericParts[0];

            //check the whole number is negative value, then separate the negative symbol from whole number
            if ($wholeNumber < 0 || $negativeNumber) {
                $negativeNumber = true;
                $positiveValues = explode('-', $wholeNumber);
                $wholeNumber = $positiveValues[1];
            }

            // Pad the rest of the length in the number string with Leading 0, to get it to the multiples of 3
            $numberLength = strlen($wholeNumber);
            // First grouping digits length
            $OddGroupLength = $numberLength % 3;
            $gapsToBeFilled = 0;
            if ($OddGroupLength > 0) {
                $gapsToBeFilled = 3 - $OddGroupLength;
            }
            $wholeNumber = str_pad($wholeNumber, $numberLength + $gapsToBeFilled, '0', STR_PAD_LEFT);
            // Split the whole number into chunks of 3 digits
            $wholeNumberParts = str_split($wholeNumber, 3);
            // Re-create the whole number with user's configured currency separator
            $numericParts[0] = $wholeNumber = implode($currencySeparator, $wholeNumberParts);
            if ($wholeNumber != 0) {
                $numericParts[0] = ltrim($wholeNumber, '0');
            } else {
                $numericParts[0] = 0;
            }

            //if its negative number, append-back the negative symbol to the whole number part
            if ($negativeNumber) {
                $numericParts[0] = '-' . $numericParts[0];
            }

            // Re-create the currency value combining the whole number and the decimal part using Decimal separator
            $number = implode($decimalSeparator, $numericParts);

            return $number;
        }
        if ($currencyPattern == $this->CURRENCY_PATTERN_MIXED_GROUPING) {
            $negativeNumber = false;
            if ($value < 0) {
                $negativeNumber = true;
            }

            // Separate the numeric and decimal parts
            $numericParts = explode('.', $value);
            $wholeNumber = $numericParts[0];

            //check the whole number is negative value, then separate the negative symbol from whole number
            if ($wholeNumber < 0 || $negativeNumber) {
                $negativeNumber = true;
                $positiveValues = explode('-', $wholeNumber);
                $wholeNumber = $positiveValues[1];
            }

            // First part of the number which needs separate division
            if (strlen($wholeNumber) > 3) {
                $wholeNumberFirstPart = substr($wholeNumber, 0, strlen($wholeNumber) - 3);
            }
            // Second Part of the number (last 3 digits) which should be separated from the First part using Currency Separator
            $wholeNumberLastPart = substr($wholeNumber, -3);
            if (!empty($wholeNumberFirstPart)) {
                // Pad the rest of the length in the number string with Leading 0, to get it to the multiples of 2
                $numberLength = strlen($wholeNumberFirstPart);
                // First grouping digits length
                $OddGroupLength = $numberLength % 2;
                $gapsToBeFilled = 0;
                if ($OddGroupLength > 0) {
                    $gapsToBeFilled = 2 - $OddGroupLength;
                }
                $wholeNumberFirstPart = str_pad($wholeNumberFirstPart, $numberLength + $gapsToBeFilled, '0', STR_PAD_LEFT);
                // Split the first part of tne number into chunks of 2 digits
                $wholeNumberFirstPartElements = str_split($wholeNumberFirstPart, 2);
                $wholeNumberFirstPart = ltrim(implode($currencySeparator, $wholeNumberFirstPartElements), '0');
                $wholeNumberFirstPart = implode($currencySeparator, $wholeNumberFirstPartElements);
                if ($wholeNumberFirstPart != 0) {
                    $wholeNumberFirstPart = ltrim($wholeNumberFirstPart, '0');
                } else {
                    $wholeNumberFirstPart = 0;
                }
                // Re-create the whole number with user's configured currency separator
                $numericParts[0] = $wholeNumberFirstPart . $currencySeparator . $wholeNumberLastPart;
            } else {
                $numericParts[0] = $wholeNumberLastPart;
            }

            //if its negative number, append-back the negative symbol to the whole number part
            if ($negativeNumber) {
                $numericParts[0] = '-' . $numericParts[0];
            }

            // Re-create the currency value combining the whole number and the decimal part using Decimal separator
            $number = implode($decimalSeparator, $numericParts);

            return $number;
        }

        return $number;
    }

}