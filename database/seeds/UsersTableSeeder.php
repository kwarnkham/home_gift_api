<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
            'name' => 'Moon',
            'mobile' => '5088369',
            'address' => 'A4',
            'password' => bcrypt('123123')
        ]);
    }
}
