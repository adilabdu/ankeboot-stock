<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Adil Abdu Bushra',
            'email' => 'adil@ankebootbooks.org',
            'password' => Hash::make('helloworld'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
