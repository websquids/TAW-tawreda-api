<?php

if (!function_exists('calcPriceWithDiscount')) {
    function calcPriceWithDiscount(float $price, float $discount): float
    {
        return $price - ($price * ($discount / 100));
    }
}
