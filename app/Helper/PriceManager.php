<?php

namespace App\Helper;

class PriceManager
{
    public const CURRENCY_SYMBOL = '$';

    /**
     * Format a float price as a string with currency symbol and two decimal places.
     *
     * @param  float  $price The price to be formatted.
     * @return string The formatted price with currency symbol and two decimal places.
     */
    public static function format(float $price): string
    {
        return ! empty($price) ? self::CURRENCY_SYMBOL.' '.number_format($price, 2) : '-';
    }
}
