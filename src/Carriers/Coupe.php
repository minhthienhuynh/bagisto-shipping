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
        $weightTotal = 0;

        if ($this->getConfigData('type') == 'per_unit') {
            foreach ($cart->items as $item) {
                if ($item->product->getTypeInstance()->isStockable()) {
                    $weightTotal += $item->product->weight;
                }
            }

            if ($weightTotal <= 100) {
                if ($stateCode = request()->get('billing')['state']) {
                    $state = CountryState::where([
                        ['country_code', 'FR'],
                        ['code', $stateCode],
                    ])->first();
                    $coupePrice = CoupePrice::where([
                        ['price', '>=', $weightTotal],
                        ['state_id', $state->id],
                    ])->orderBy('price')->first();

                    if ($coupePrice) {
                        $object->price = $object->base_price = $coupePrice->price;
                    }
                }
            }
        }

        return $object;
    }
}
