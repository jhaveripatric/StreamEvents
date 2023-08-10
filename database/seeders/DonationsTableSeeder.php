<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Donation;
use Faker\Factory as Faker;

class DonationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 500) as $index) {
            Donation::create([
                'amount' => $faker->randomFloat(2, 1, 1000),
                'currency' => $faker->randomElement(['USD', 'EUR', 'GBP']),
                'donation_message' => $faker->sentence,
                'read' => $faker->boolean(50),
                'created_at' => now()->subDays(rand(1, 90)),
            ]);
        }
    }
}
