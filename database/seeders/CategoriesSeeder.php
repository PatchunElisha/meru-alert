<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $keys = ['id', 'first_id', 'second_id', 'category'];
        $csv = explode("\n", Storage::disk('local')->get("seed/category.csv"));

        $values = [];
        foreach ($csv as $key => $value) {
            if (!$value) continue;
            $values[] = explode(",", $value);
        }
        foreach ($values as $value) {
            $categories[] = array_combine($keys, $value);
        }

        foreach ($categories as $category) {
            Categories::create($category);
        }
    }
}
