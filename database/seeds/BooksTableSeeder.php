<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(0, 50) as $books) {
            $price = $faker->randomFloat(2, 4.99, 300.00);
            DB::table('books')->insert([
                'name' => $faker->words(3, true),
//                'title' => $faker->sentence
                'author' => $faker->name,
                'published_year' => $faker->year,
                'isbn' => $faker->isbn10,
                'cost_price' => $price,
                'selling_price' => $price * 1.49,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}