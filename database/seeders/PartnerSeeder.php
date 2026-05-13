<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Locale Indonesia

        for ($i = 0; $i < 5; $i++) {
            DB::table('partners')->insert([
                'name'       => $faker->company(),
                'logo_url'   => 'https://placehold.co/200x200?text=' . urlencode($faker->word()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
