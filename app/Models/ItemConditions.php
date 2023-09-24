<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemConditions extends Model
{
    protected $fillable = [
        'id',
        'item_condition'
    ];

    /**
     * ItemConditionSearchList に対して1対多
     *
     * @return HasMany
     */
    public function itemConditionSearchLists(): HasMany
    {
        return $this->hasMany('App\ItemConditionSearchList');
    }
}
