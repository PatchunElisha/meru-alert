<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchLists extends Model
{
    protected $fillable = [
        'users_id',
        'keyword',
        'exclude_keyword',
        // 'category_id',
        // 'brand_id',
        // 'size_id',
        'price_min',
        'price_max',
        // 'item_condition_id',
        // 'shipping_payer_id',
        // 'color_id',
        // 'shipping_method_id',
    ];

    // public function Categories()
    // {
    //     return $this->hasMany('App\Categories');
    // }

    // public function Brands()
    // {
    //     return $this->hasMany('App\Brands');
    // }

    // public function Sizes()
    // {
    //     return $this->hasMany('App\Sizes');
    // }

    // public function ItemConditionLists()
    // {
    //     return $this->hasMany('App\ItemConditionList');
    // }

    // public function ShippingPayers()
    // {
    //     return $this->hasMany('App\ShippingPayers');
    // }

    // public function ShippingMethods()
    // {
    //     return $this->hasMany('App\ShippingMethods');
    // }

    // public function SearchResultStocks()
    // {
    //     return $this->hasMany(SearchResultStocks::class);
    // }
}
