<?php
/**
 * Created by PhpStorm.
 * User: sanderhagenaars
 * Date: 2019-05-16
 * Time: 10:07
 */

namespace BetterCurrency\ORM\FieldType;


class Currency extends \SilverStripe\ORM\FieldType\DBCurrency
{
    /**
     * On which side of the value, is the currency symbol placed?
     *
     * Can have values "before" or "after"
     *
     * @config string
     */
    private static $currency_symbol_position = 'before';

    private static $thousand_seperator = ',';
    private static $decimal_point = '.';

    /**
     * Returns the number as a currency, eg “$1,000.00”.
     */
    public function Nice()
    {
        $val = $this->positionSymbol($this->amountFormat($this->value, 2));
        if ($this->value < 0) {
            return "($val)";
        }

        return $val;
    }

    /**
     * Returns the number as a whole-number currency, eg “$1,000”.
     */
    public function Whole()
    {
        $val = $this->positionSymbol($this->amountFormat($this->value, 0));
        if ($this->value < 0) {
            return "($val)";
        }

        return $val;
    }

    /**
     * @param     $val
     * @param int $decimals
     * @return string
     */
    protected function amountFormat($val, $decimals = 2)
    {
        return number_format(abs($this->value), $decimals, $this->config()->decimal_point, $this->config()->thousand_seperator);
    }

    /**
     * @param $value
     * @return string
     */
    protected function positionSymbol($value)
    {
        $symbol = $this->config()->currency_symbol;
        $position = $this->config()->currency_symbol_position;
        switch ($position) {
            case 'before':
                return $symbol . $value;
            case 'after':
                return $value . $symbol;
            default:
                return $symbol . $value;
        }
    }

}