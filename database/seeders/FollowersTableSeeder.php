<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Follower;
use Faker\Factory as Faker;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 500) as $index) {
            Follower::create([
                'name' => $faker->name,
                'read' => $faker->boolean(50),
                'created_at' => now()->subDays(rand(1, 90)),
            ]);
        }
    }
}
