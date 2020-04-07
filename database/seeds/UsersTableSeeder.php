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
            'name' => 'Ko Fatty',
            'mobile' => '0000000',
            'address' => 'A4',
            'password' => bcrypt('00000'),
            'is_admin' => true
        ]);
    }
}
