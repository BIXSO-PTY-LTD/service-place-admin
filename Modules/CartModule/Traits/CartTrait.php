<?php

namespace Modules\CartModule\Traits;

use Modules\CartModule\Entities\Cart;
use Modules\ServiceManagement\Entities\Service;

trait CartTrait
{
    /**
     * @param $cartId
     * @param $quantity
     * @return bool
     */
    public function updateCartQuantity($cartId, $quantity): bool
    {
        $cart = Cart::find($cartId);
        $service = Service::with(['service_discount', 'campaign_discount'])->find($cart['service_id']);

        if (!isset($cart) || !isset($service)) return false;

        $basicDiscount = basic_discount_calculation($service, $cart->service_cost * $quantity);
        $campaignDiscount = campaign_discount_calculation($service, $cart->service_cost * $quantity);
        $subtotal = round($cart->service_cost * $quantity, 2);

        $applicableDiscount = ($campaignDiscount >= $basicDiscount) ? $campaignDiscount : $basicDiscount;
        $tax = round(((($cart->service_cost*$quantity - $applicableDiscount) * $service['tax']) / 100), 2);

        //between normal discount & campaign discount, greater one will be calculated
        $basicDiscount = $basicDiscount > $campaignDiscount ? $basicDiscount : 0;
        $campaignDiscount = $campaignDiscount >= $basicDiscount ? $campaignDiscount : 0;

        $cart->quantity = $quantity;
        $cart->discount_amount = $basicDiscount;
        $cart->campaign_discount = $campaignDiscount;
        $cart->coupon_discount = 0;
        $cart->coupon_code = null;
        $cart->tax_amount = round($tax, 2);
        $cart->total_cost = round($subtotal - $basicDiscount - $campaignDiscount + $tax, 2);
        $cart->save();

        return true;
    }

}
