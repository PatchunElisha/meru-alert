<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchResultStocks extends Model
{
    protected $fillable = [
        'id',
        'search_lists_id',
        'product_name',
        'price',
        'url',
        'image_url',
    ];

    /**
     * 検索対象から取得した10件目以降のレコードを作成日の古い順に削除する
     *
     * @param integer $id
     * @param integer $records_to_keep
     * @return void
     */
    public function deleteExcessRecords($id, $records_to_keep = 10): void
    {
        $recordCount = $this->where('search_lists_id', $id)->count();

        if ($recordCount > $records_to_keep) {
            $recordsToDelete = $this->where('search_lists_id', $id)
                ->orderBy('created_at')
                ->limit($recordCount - $records_to_keep)
                ->get();

            foreach ($recordsToDelete as $record) {
                $record->delete();
            }
        }
    }
}
