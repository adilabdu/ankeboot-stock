<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach(range(1,10) as $indx) {
            DB::table('books')->insert([
                'name' => $faker->words(3),
                'author' => $faker->firstName . " " . $faker->lastName,
                'published_year' => $faker->year,
                'ISBN' => $faker->isbn13,
                'cost_price' => $faker->randomFloat(2, 1, 50),
                'selling_price' => $faker->randomFloat(2, 1, 50) + 7.49,
            ]);
        }
    }
}
