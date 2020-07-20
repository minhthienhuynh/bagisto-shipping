<?php

namespace DFM\Shipping\Carriers;

use DFM\Shipping\Models\CoupePrice;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Core\Models\CountryState;
use Webkul\Shipping\Carriers\AbstractShipping;

class Coupe extends AbstractShipping
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'coupe';

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

        $object->carrier = 'coupe';
        $object->carrier_title = $this->getConfigData('title');
        $object->method = 'coupe_coupe';
        $object->method_title = $this->getConfigData('title');
        $object->method_description = $this->getConfigData('description');
        $object->price = 0;
        $object->base_price = 0;

        if ($this->getConfigData('type') == 'per_unit') {
            if (($countryCode = request()->get('billing')['country']) != 'FR') {
                return false;
            }

            if (! ($stateCode = request()->get('billing')['state'])) {
                return false;
            }

            if (! ($state = CountryState::where([['country_code', $countryCode], ['code', $stateCode]])->first())) {
                return false;
            }

            $weightTotal = 0;
            foreach ($cart->items as $item) {
                if ($item->product->getTypeInstance()->isStockable()) {
                    $weightTotal += $item->product->weight;
                }
            }

            if ($weightTotal < 10 || $weightTotal > 100) {
                return false;
            }

            if (! ($coupePrice = CoupePrice::where([['weight', '>=', $weightTotal], ['state_id', $state->id]])->orderBy('price')->first())) {
                return false;
            }

            $object->price = $object->base_price = $coupePrice->price;
        }

        return $object;
    }
}
