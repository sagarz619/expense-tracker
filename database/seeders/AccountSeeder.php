<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Cash',
                'type' => 'cash',
                'opening_balance' => 0,
                'current_balance' => 0,
                'icon' => 'wallet',
                'color' => '#4CAF50',
                'is_active' => true,
            ],
            [
                'name' => 'Bank Account',
                'type' => 'bank',
                'opening_balance' => 0,
                'current_balance' => 0,
                'icon' => 'university',
                'color' => '#2196F3',
                'is_active' => true,
            ],
        ];

        foreach ($accounts as $account) {
            DB::table('accounts')->insert([
                'name' => $account['name'],
                'type' => $account['type'],
                'opening_balance' => $account['opening_balance'],
                'current_balance' => $account['current_balance'],
                'icon' => $account['icon'],
                'color' => $account['color'],
                'is_active' => $account['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
