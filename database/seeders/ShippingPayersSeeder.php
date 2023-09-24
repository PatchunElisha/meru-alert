<?php

namespace Database\Seeders;

use App\Models\ShippingPayers;
use Illuminate\Database\Seeder;

class ShippingPayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keys = ['id', 'shipping_payer'];
        $values = [
            [1, '着払い(購入者負担)'],
            [2, '送料込み(出品者負担)'],
        ];
        foreach ($values as $value) {
            $payers[] = array_combine($keys, $value);
        }

        foreach ($payers as $payer) {
            ShippingPayers::create($payer);
        }
    }
}
