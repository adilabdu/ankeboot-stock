<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $invoices = [];
        foreach (range(0, 20) as $item) {
            $invoices[] = '00' . $item . '2020';
        }

        $faker = Faker::create();

        foreach (range(0, 120) as $stocks) {

            $date = $faker->dateTimeBetween('-3 months', 'now');
            $price = $faker->randomFloat(2, 4.99, 300.00);

            DB::table('stocks')->insert([
                'invoice' => $invoices[$faker->numberBetween(0, 20)],
                'pkg' => $faker->numberBetween(0, 2) % 2 ? true : false,
                'received_amount' => $faker->numberBetween(1, 2) % 2 ? $faker->numberBetween(0, 200) : 0,
                'issued_amount' => $faker->numberBetween(1, 2) % 2 ? $faker->numberBetween(0, 150) : 0,
                'cost_price' => $price,
                'book_id' => $faker->numberBetween(0, 50),
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
