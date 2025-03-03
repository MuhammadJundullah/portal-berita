<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed user terlebih dahulu
        User::factory()->create([
            'name' => 'admin',
            'password' => bcrypt('admin'),
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'preferences' => json_encode(['theme' => 'dark']),
        ]);

        // Seed news setelah users
        // $this->call(NewSeeder::class);

        // Seed user_interactions setelah news
        // $this->call(UserInteractionsSeeder::class);

        // Seed news categories setelah comments
        $this->call(NewsCategorySeeder::class);
    }

}