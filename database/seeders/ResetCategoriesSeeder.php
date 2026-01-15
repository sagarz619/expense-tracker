<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\RecurringTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Delete all transactions and recurring transactions
        Transaction::truncate();
        RecurringTransaction::truncate();

        // Delete all categories
        Category::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Expense Categories
        $expenseCategories = [
            [
                'name' => 'Bills & Utilities',
                'description' => 'Rent, Electricity, Internet, Mobile Recharge, Maintenance',
                'type' => 'expense',
                'icon' => 'file-invoice-dollar',
                'color' => '#9966FF',
            ],
            [
                'name' => 'Grocery',
                'description' => 'Fruits and Vegetables, Chicken etc',
                'type' => 'expense',
                'icon' => 'shopping-basket',
                'color' => '#4BC0C0',
            ],
            [
                'name' => 'Food & Dining',
                'description' => 'Restaurant, Lunch, Fastfood, Swiggy/Zomato',
                'type' => 'expense',
                'icon' => 'utensils',
                'color' => '#FF6384',
            ],
            [
                'name' => 'Vices',
                'description' => 'Cigarette / Alcohol',
                'type' => 'expense',
                'icon' => 'smoking',
                'color' => '#FF9F40',
            ],
            [
                'name' => 'Transportation',
                'description' => 'Fuel, Cab, Bike Maintenance, Parking, Toll',
                'type' => 'expense',
                'icon' => 'car',
                'color' => '#36A2EB',
            ],
            [
                'name' => 'Healthcare',
                'description' => 'Consultation, Medicines, Other',
                'type' => 'expense',
                'icon' => 'heartbeat',
                'color' => '#FF6384',
            ],
            [
                'name' => 'Shopping',
                'description' => 'Clothes, Electronics, Accessories',
                'type' => 'expense',
                'icon' => 'shopping-bag',
                'color' => '#FFCE56',
            ],
            [
                'name' => 'Personal',
                'description' => 'Salon, Grooming, Self-care',
                'type' => 'expense',
                'icon' => 'user',
                'color' => '#C9CBCF',
            ],
            [
                'name' => 'Travel & Trips',
                'description' => 'Vacation, Hotel, Sightseeing',
                'type' => 'expense',
                'icon' => 'plane',
                'color' => '#4BC0C0',
            ],
            [
                'name' => 'Entertainment',
                'description' => 'Movies, Parties, OTT, Games',
                'type' => 'expense',
                'icon' => 'gamepad',
                'color' => '#9966FF',
            ],
            [
                'name' => 'Education',
                'description' => 'Courses, Books, Learning',
                'type' => 'expense',
                'icon' => 'book',
                'color' => '#36A2EB',
            ],
            [
                'name' => 'Financial Bills',
                'description' => 'EMI, Loan, Bank Charges, Penalties',
                'type' => 'expense',
                'icon' => 'credit-card',
                'color' => '#FF6384',
            ],
            [
                'name' => 'Gifts & Donations',
                'description' => 'Donations, Gifts',
                'type' => 'expense',
                'icon' => 'gift',
                'color' => '#FF9F40',
            ],
            [
                'name' => 'Miscellaneous',
                'description' => 'Sudden Unplanned Expenses',
                'type' => 'expense',
                'icon' => 'question-circle',
                'color' => '#C9CBCF',
            ],
            [
                'name' => 'Savings',
                'description' => 'Emergency Fund, Savings Account',
                'type' => 'expense',
                'icon' => 'piggy-bank',
                'color' => '#4BC0C0',
            ],
            [
                'name' => 'Investments',
                'description' => 'Stock, MF, Crypto, Gold',
                'type' => 'expense',
                'icon' => 'chart-line',
                'color' => '#36A2EB',
            ],
            [
                'name' => 'Digital Subscriptions',
                'description' => 'Netflix, Spotify, Apps, Software',
                'type' => 'expense',
                'icon' => 'laptop',
                'color' => '#9966FF',
            ],
        ];

        // Income Categories
        $incomeCategories = [
            [
                'name' => 'Salary',
                'description' => 'Monthly Salary, Bonus',
                'type' => 'income',
                'icon' => 'money-bill-wave',
                'color' => '#10B981',
            ],
            [
                'name' => 'Freelance',
                'description' => 'Freelance Projects, Consulting',
                'type' => 'income',
                'icon' => 'laptop-code',
                'color' => '#3B82F6',
            ],
            [
                'name' => 'Commission',
                'description' => 'Sales Commission, Referrals',
                'type' => 'income',
                'icon' => 'percentage',
                'color' => '#8B5CF6',
            ],
            [
                'name' => 'Side Hustle',
                'description' => 'Business, Extra Income',
                'type' => 'income',
                'icon' => 'briefcase',
                'color' => '#F59E0B',
            ],
        ];

        // Insert all categories
        foreach ($expenseCategories as $category) {
            Category::create($category);
        }

        foreach ($incomeCategories as $category) {
            Category::create($category);
        }

        $this->command->info('Categories reset successfully!');
        $this->command->info('Created ' . count($expenseCategories) . ' expense categories');
        $this->command->info('Created ' . count($incomeCategories) . ' income categories');
    }
}
