<?php

namespace Database\Seeders;

use Database\Seeders\DataSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        for ($i = 0; $i < 2; ++$i) {
            $this->call(DataSeeder::class);
        }
    }
}
