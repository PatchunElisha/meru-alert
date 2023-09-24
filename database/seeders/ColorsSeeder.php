<?php

namespace Database\Seeders;

use App\Models\Colors;
use Illuminate\Database\Seeder;

class ColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keys = ['id', 'color'];
        $values = [
            [1, 'ブラック系'],
            [2, 'ホワイト系'],
            [3, 'グレイ系'],
            [4, 'ブラウン系'],
            [5, 'レッド系'],
            [6, 'ピンク系'],
            [7, 'パープル系'],
            [8, 'ブルー系'],
            [9, 'ベージュ系'],
            [10, 'グリーン系'],
            [11, 'イエロー系'],
            [12, 'オレンジ系'],
        ];
        foreach ($values as $value) {
            $colors[] = array_combine($keys, $value);
        }

        foreach ($colors as $color) {
            Colors::create($color);
        }
    }
}
