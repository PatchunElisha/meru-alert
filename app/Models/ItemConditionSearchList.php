<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemConditionSearchList extends Model
{
    protected $fillable = [
        'id',
        'item_condition_id',
    ];

    /**
     * 主関係ItemConditions のレコードを紐付ける
     *
     * @return BelongsTo
     */
    public function itemConditions(): BelongsTo
    {
        return $this->belongsTo('App\ItemConditions');
    }

    /**
     * 主関係SearchLists のレコードを紐付ける
     *
     * @return BelongsTo
     */
    public function searchLists(): BelongsTo
    {
        return $this->belongsTo('App\SearchLists');
    }
}
