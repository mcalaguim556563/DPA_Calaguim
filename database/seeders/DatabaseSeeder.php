<?php

namespace Database\Seeders;

use App\Models\RiceItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@dpa.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // Seed sample rice items
        $riceItems = [
            ['name' => 'Jasmine',   'price_per_kg' => 52.00, 'stock_quantity' => 150, 'description' => 'Fragrant long-grain Thai jasmine rice.'],
            ['name' => 'Brown Rice','price_per_kg' => 58.00, 'stock_quantity' => 80,  'description' => 'Whole grain, high in fiber and nutrients.'],
            ['name' => 'Dinorado', 'price_per_kg' => 65.00, 'stock_quantity' => 120, 'description' => 'Premium Mindanao variety, soft and aromatic.'],
            ['name' => 'Sinandomeng','price_per_kg'=> 48.00, 'stock_quantity' => 200, 'description' => 'Most popular rice variety in the Philippines.'],
            ['name' => 'Milagrosa', 'price_per_kg' => 55.00, 'stock_quantity' => 100, 'description' => 'Slender grain with slightly sweet flavor.'],
        ];

        foreach ($riceItems as $item) {
            RiceItem::firstOrCreate(['name' => $item['name']], $item);
        }
    }
}
