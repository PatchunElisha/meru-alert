<?php

namespace Database\Seeders;

use App\Models\ItemConditions;
use Illuminate\Database\Seeder;

class ItemConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keys = ['id', 'item_condition'];
        $values = [
            [1, '新品、未使用'],
            [2, '未使用に近い'],
            [3, '目立った傷や汚れなし'],
            [4, 'やや傷や汚れあり'],
            [5, '傷や汚れあり'],
            [6, '全体的に状態が悪い'],
        ];
        foreach ($values as $value) {
            $items[] = array_combine($keys, $value);
        }

        foreach ($items as $item) {
            ItemConditions::create($item);
        }
    }
}
