<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderTracking;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(1000)->create();

        User::factory(5000)->create()->each(function ($user) {
            if (rand(1, 100) <= 10) {
                return;
            }

            $user->orders()->saveMany(
                Order::factory(rand(2, 6))->make()
            )->each(function ($order) {
                $products = Product::inRandomOrder()->take(rand(1, 5))->get();
                $pivotData = [];
                foreach ($products as $product) {
                    $pivotData[$product->id] = ['quantity' => rand(1, 10)];
                }

                $order->products()->attach($pivotData);

                $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
                $currentStatusIndex = 0;
                $order->trackings()->saveMany(
                    collect(range(1, rand(2, 5)))->map(function () use (&$currentStatusIndex, $statuses) {
                        return OrderTracking::factory()->make([
                            'status' => $statuses[$currentStatusIndex++ % count($statuses)]
                        ]);
                    })
                );
            });
        });
    }
}
