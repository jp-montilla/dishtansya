<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 200) as $index)  {
            Product::create([
                'name' => "Product $index",
                'available_stock' => $faker->numberBetween($min = 1, $max = 100),
            ]);
        }
    }
}
