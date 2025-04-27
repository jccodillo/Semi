<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Call the UserSeeder first
        $this->call(UserSeeder::class);
        
        // Seed supplies inventory with all items
        $this->call(SuppliesInventoryItemsSeeder::class);
    }
}

