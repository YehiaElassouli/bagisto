<?php

namespace Webkul\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;
use Webkul\Cart\Models\CartItem;
use Webkul\Cart\Models\CartAddress;
use Webkul\Cart\Models\CartPayment;
use Webkul\Cart\Models\CartShippingRate;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = ['customer_id', 'session_id', 'channel_id', 'coupon_code', 'is_gift', 'global_currency_code', 'base_currency_code', 'store_currency_code', 'quote_currency_code', 'grand_total', 'base_grand_total', 'sub_total', 'base_sub_total', 'sub_total_with_discount', 'base_sub_total_with_discount', 'checkout_method', 'is_guest', 'customer_full_name', 'conversion_time'];

    protected $hidden = ['coupon_code'];

    public function items() {
        return $this->hasMany(CartItem::class)->whereNull('parent_id');
    }

    /**
     * Get the addresses for the cart.
     */
    public function addresses()
    {
        return $this->hasMany(CartAddress::class);
    }

    /**
     * Get the biling address for the cart.
     */
    public function billing_address()
    {
        return $this->addresses()->where('address_type', 'billing');
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getBillingAddressAttribute()
    {
        return $this->billing_address()->first();
    }

    /**
     * Get the shipping address for the cart.
     */
    public function shipping_address()
    {
        return $this->addresses()->where('address_type', 'shipping');
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getShippingAddressAttribute()
    {
        return $this->shipping_address()->first();
    }

    /**
     * Get the shipping rates for the cart.
     */
    public function shipping_rates()
    {
        return $this->hasManyThrough(CartShippingRate::class, CartAddress::class, 'cart_id', 'cart_address_id');
    }

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getSelectedShippingRateAttribute()
    {
        return $this->shipping_rates()->where('method', $this->shipping_method)->first();
    }

    /**
     * Get the payment associated with the cart.
     */
    public function payment()
    {
        return $this->hasOne(CartPayment::class);
    }
}