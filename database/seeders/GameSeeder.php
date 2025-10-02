<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $games = [
            [
                'name' => 'Mobile Legends',
                'description' => 'Popular MOBA game',
                'logo' => 'images/mobile_legends.png',
            ],
            [
                'name' => 'Roblox',
                'description' => 'Gaming platform',
                'logo' => 'images/roblox.png',
            ],
        ];

        foreach ($games as $game) {
            DB::table('games')->insert([
                'name' => $game['name'],
                'description' => $game['description'],
                'logo' => $game['logo'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Add topup options for Mobile Legends (game_id: 1)
        $mlOptions = [
            ['game_id' => 1, 'coins' => 100, 'amount' => '100 Diamonds', 'price' => 15000],
            ['game_id' => 1, 'coins' => 250, 'amount' => '250 Diamonds', 'price' => 35000],
            ['game_id' => 1, 'coins' => 500, 'amount' => '500 Diamonds', 'price' => 70000],
        ];

        // Add topup options for Roblox (game_id: 2)
        $robloxOptions = [
            ['game_id' => 2, 'coins' => 400, 'amount' => '400 Robux', 'price' => 50000],
            ['game_id' => 2, 'coins' => 800, 'amount' => '800 Robux', 'price' => 95000],
            ['game_id' => 2, 'coins' => 1700, 'amount' => '1700 Robux', 'price' => 200000],
        ];

        $allOptions = array_merge($mlOptions, $robloxOptions);
        
        foreach ($allOptions as $option) {
            DB::table('topup_options')->insert([
                'game_id' => $option['game_id'],
                'coins' => $option['coins'],
                'amount' => $option['amount'],
                'price' => $option['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}