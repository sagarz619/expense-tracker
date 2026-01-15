<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'currency', 'value' => '₹'],
            ['key' => 'currency_symbol', 'value' => '₹'],
            ['key' => 'month_start_day', 'value' => '1'],
            ['key' => 'financial_year_start_month', 'value' => '4'],
            ['key' => 'app_name', 'value' => 'Expense Tracker'],
            ['key' => 'date_format', 'value' => 'd/m/Y'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert([
                'key' => $setting['key'],
                'value' => $setting['value'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
