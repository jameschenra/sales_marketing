<?php
namespace App\Helpers;

class PriceHelper
{
    public static function getFee($price = 0) {
        /* $options = unserialize($store->options);
        $isFreeService = array_get($options, 'free_service');

        if ($isFreeService == '1') {
            $fee = FEE;
        } else {
            $fee = ($price * FEE_PERCENT / 100);
        } */

        if ($price == 0) {
            $fee = FEE;
        } else {
            $fee = ($price * FEE_PERCENT / 100);
        }

        return $fee;
    }

    public static function getMaxServiceDiscountPrice($price) {
        $discount = 0;
        if ($price <= 10) {
            $discount = 1;
        } else if ($price <= 20) {
            $discount = 2;
        } else if ($price <= 40) {
            $discount = 4;
        } else if ($price <= 60) {
            $discount = 6;
        } else if ($price <= 100) {
            $discount = 10;
        } else if ($price <= 150) {
            $discount = 15;
        } else if ($price <= 500) {
            $discount = 20;
        } else {
            $discount = 30;
        }
        
        return $price - $discount;
    }
}