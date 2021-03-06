<?php

namespace DFM\Leleu\Carriers;

use DFM\Shipping\Models\LeleuPrice;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Core\Models\CountryState;
use Webkul\Shipping\Carriers\AbstractShipping;


class Leleu extends AbstractShipping
{
    /**
     * Shipment method code
     *
     * @var string
     */
    protected $code  = 'leleu';

    /**
     * @return bool|CartShippingRate
     */
    public function calculate()
    {
        if (! $this->isAvailable()) {
            return false;
        }

        $cart = Cart::getCart();

        $object = new CartShippingRate;

        $object->carrier = 'leleu';
        $object->carrier_title = $this->getConfigData('title');
        $object->method = 'leleu_leleu';
        $object->method_title = $this->getConfigData('title');
        $object->method_description = $this->getConfigData('description');
        $object->price = 0;
        $object->base_price = 0;

        if ($this->getCartWeight($cart) <= 100) {
            return false;
        }

        $cartVolume = $this->getCartVolume($cart);

        if (($countryCode = request()->get('billing')['country']) == 'FR' && ($stateCode = request()->get('billing')['state'])) {
            if (! ($state = CountryState::where([['country_code', $countryCode], ['code', $stateCode]])->first())) {
                return false;
            }

            if (! ($leleuPrice = LeleuPrice::where([['volume', '>=', $cartVolume], ['state_id', $state->id]])->orderBy('price')->first())) {
                return false;
            }
        } elseif (! ($leleuPrice = LeleuPrice::where([['volume', '>=', $cartVolume], ['state_id', null]])->orderBy('price')->first())) {
            return false;
        }

        $object->price = $object->base_price = $leleuPrice->price;

        return $object;
    }

    /**
     * @param  Cart  $cart
     * @return int
     */
    private function getCartVolume(Cart $cart)
    {
        return rand(1, 6);
    }

    /**
     * @param  Cart  $cart
     * @return int
     */
    private function getCartWeight(Cart $cart)
    {
        $weight = 0;

        foreach ($cart->items as $item) {
            if ($item->product->getTypeInstance()->isStockable()) {
                $weight += $item->product->weight;
            }
        }

        return $weight;
    }
}
