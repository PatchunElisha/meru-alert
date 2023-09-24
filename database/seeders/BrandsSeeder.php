<?php

namespace Database\Seeders;

use App\Models\Brands;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keys = ['id', 'brand', 'sub_brand'];
        $csv = explode("\n", Storage::disk('local')->get("seed/brand.csv"));

        $values = [];
        foreach ($csv as $key => $value) {
            if (!$value) continue;
            $values[] = explode(",", $value);
        }
        foreach ($values as $value) {
            $brands[] = array_combine($keys, $value);
        }

        foreach ($brands as $brand) {
            Brands::create($brand);
        }
    }
}
