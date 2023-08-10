<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MerchSale;
use Faker\Factory as Faker;

class MerchSalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 500) as $index) {
            MerchSale::create([
                'item_name' => $faker->word,
                'amount' => $faker->randomNumber(2),
                'price' => $faker->randomFloat(2, 10, 200),
                'read' => $faker->boolean(50),
                'created_at' => now()->subDays(rand(1, 90)),
            ]);
        }
    }
}
