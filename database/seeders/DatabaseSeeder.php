<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'sagarz1993@gmail.com',
            'password' => bcrypt('msiGTX_780'),
        ]);

        // Run other seeders
        $this->call([
            SettingSeeder::class,
            CategorySeeder::class,
            AccountSeeder::class,
        ]);
    }
}
