<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 5; $i++) {
            DB::table('partners')->insert([
                'name' => fake()->company(),
                'logo_url' => fake()->imageUrl(200, 200, 'business', true),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}