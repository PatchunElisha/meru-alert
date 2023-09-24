<?php

namespace Database\Seeders;

use App\Models\Sizes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keys = ['id', 'first_id', 'size'];
        $csv = explode("\n", Storage::disk('local')->get("seed/size.csv"));

        $values = [];
        foreach ($csv as $key => $value) {
            if (!$value) continue;
            $values[] = explode(",", $value);
        }
        foreach ($values as $value) {
            $sizes[] = array_combine($keys, $value);
        }

        foreach ($sizes as $size) {
            Sizes::create($size);
        }
    }
}
