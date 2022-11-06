<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();

        $json = File::get("database/data/categories.json");
        $categories = json_decode($json);

        $categories = array_map(function ($item) {
            return ['name' => $item->name, 'created_at' => now(), 'updated_at' => now()];
        }, $categories);

        Category::insert($categories);
    }
}
