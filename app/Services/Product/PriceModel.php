<?php

namespace App\Services\Product;

abstract class PriceModel
{
    /**
     * Platí standartní cena u zboží nebo varianty
     */
    const NORMAL = 'normal';

    /**
     * Platí akční cena u zboží nebo varianty
     */
    const ACTION = 'action';

    /**
     * Platí vip cena u zboží nebo varianty
     */
    const VIP = 'vip';
}
