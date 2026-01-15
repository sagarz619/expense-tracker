<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseCategories = [
            ['name' => 'Food & Dining', 'type' => 'expense', 'icon' => 'utensils', 'color' => '#FF6384'],
            ['name' => 'Transportation', 'type' => 'expense', 'icon' => 'car', 'color' => '#36A2EB'],
            ['name' => 'Shopping', 'type' => 'expense', 'icon' => 'shopping-bag', 'color' => '#FFCE56'],
            ['name' => 'Entertainment', 'type' => 'expense', 'icon' => 'film', 'color' => '#4BC0C0'],
            ['name' => 'Bills & Utilities', 'type' => 'expense', 'icon' => 'file-invoice', 'color' => '#9966FF'],
            ['name' => 'Healthcare', 'type' => 'expense', 'icon' => 'heartbeat', 'color' => '#FF9F40'],
            ['name' => 'Education', 'type' => 'expense', 'icon' => 'graduation-cap', 'color' => '#FF6384'],
            ['name' => 'Personal Care', 'type' => 'expense', 'icon' => 'spa', 'color' => '#C9CBCF'],
            ['name' => 'Home & Rent', 'type' => 'expense', 'icon' => 'home', 'color' => '#36A2EB'],
            ['name' => 'Insurance', 'type' => 'expense', 'icon' => 'shield', 'color' => '#FFCE56'],
            ['name' => 'Gifts & Donations', 'type' => 'expense', 'icon' => 'gift', 'color' => '#4BC0C0'],
            ['name' => 'Travel', 'type' => 'expense', 'icon' => 'plane', 'color' => '#9966FF'],
            ['name' => 'Other Expenses', 'type' => 'expense', 'icon' => 'ellipsis-h', 'color' => '#FF9F40'],
        ];

        $incomeCategories = [
            ['name' => 'Salary', 'type' => 'income', 'icon' => 'money-bill-wave', 'color' => '#4CAF50'],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => 'laptop-code', 'color' => '#8BC34A'],
            ['name' => 'Business', 'type' => 'income', 'icon' => 'briefcase', 'color' => '#CDDC39'],
            ['name' => 'Investments', 'type' => 'income', 'icon' => 'chart-line', 'color' => '#9CCC65'],
            ['name' => 'Interest', 'type' => 'income', 'icon' => 'percentage', 'color' => '#AED581'],
            ['name' => 'Rental Income', 'type' => 'income', 'icon' => 'building', 'color' => '#C5E1A5'],
            ['name' => 'Bonus', 'type' => 'income', 'icon' => 'gift', 'color' => '#DCE775'],
            ['name' => 'Other Income', 'type' => 'income', 'icon' => 'plus-circle', 'color' => '#E6EE9C'],
        ];

        foreach (array_merge($expenseCategories, $incomeCategories) as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'type' => $category['type'],
                'icon' => $category['icon'],
                'color' => $category['color'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
