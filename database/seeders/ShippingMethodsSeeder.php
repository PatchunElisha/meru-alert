<?php

namespace Database\Seeders;

use App\Models\ShippingMethods;
use Illuminate\Database\Seeder;

class ShippingMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keys = ['name', 'shipping_method'];
        $values = [
            ['anonymous', '匿名配送'],
            ['japan_post', '郵便局/コンビニ受取'],
            ['no_option', 'オプションなし'],
        ];
        foreach ($values as $value) {
            $methods[] = array_combine($keys, $value);
        }

        foreach ($methods as $method) {
            ShippingMethods::create($method);
        }
    }
}
