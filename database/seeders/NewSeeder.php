<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('news')->insert([
            [
                'link' => 'https://example.com/news1',
                'headline' => 'Breaking News 1',
                'category' => 'World',
                'short_description' => 'This is a short description for news 1.',
                'authors' => 'Author 1',
                'date' => '2023-10-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'link' => 'https://example.com/news2',
                'headline' => 'Breaking News 2',
                'category' => 'Technology',
                'short_description' => 'This is a short description for news 2.',
                'authors' => 'Author 2',
                'date' => '2023-10-02',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'link' => 'https://example.com/news3',
                'headline' => 'Breaking News 3',
                'category' => 'Sports',
                'short_description' => 'This is a short description for news 3.',
                'authors' => 'Author 3',
                'date' => '2023-10-03',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
