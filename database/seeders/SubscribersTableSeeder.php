<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscriber;
use Faker\Factory as Faker;

class SubscribersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $subscriptionTiers = ['Tier1', 'Tier2', 'Tier3'];

        foreach (range(1, 500) as $index) {
            Subscriber::create([
                'name' => $faker->name,
                'subscription_tier' => $subscriptionTiers[array_rand($subscriptionTiers)],
                'read' => $faker->boolean(50),
                'created_at' => now()->subDays(rand(1, 90)),
            ]);
        }
    }
}
