<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::truncate();

        //Read  products JSON
        $json = File::get("database/data/products.json");
        $products = json_decode($json);

        $products = array_map(function ($item) {
            return [
                'name' => $item->title,
                'description' => $item->description,
                'created_at' => now(),
                'updated_at' => now()
            ];
        },  $products);

        Product::insert($products);


        Product::chunk(5, function ($products) {

            $categoryIds = Category::all()->pluck('id');

            $numberOfCategories = $categoryIds->random(rand(1, 3))->toArray();

            foreach ($products as $product) {
                $product->categories()->attach($numberOfCategories);
            }
        });
    }
}
