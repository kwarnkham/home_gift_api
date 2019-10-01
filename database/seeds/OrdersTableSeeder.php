<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $order = App\Order::create([
            'user_id' => 1,
            'name' => 'Moon',
            'mobile' => '09797167172',
            'address' => 'A4',
            'payment' => 'cash',
            'delivery_fees' => 1000,
            'amount' => 10000
        ]);
        $order->items()->attach(1, [
            'name' => 'item name',
            'quantity' => 1,
            'price' => 1000,
            'description' => 'description...',
            'notice' => 'notice...',
            'weight' => 1.1,
            'location_id' => 1,
            'merchant_id' => 1
        ]);
    }
}
