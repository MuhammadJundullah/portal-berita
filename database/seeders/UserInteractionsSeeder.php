<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserInteractionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::find(1);
        $user2 = User::find(2);
        $user3 = User::find(3);

        $news1 = News::find(1);
        $news2 = News::find(2);

        // Cek jika data user dan news ada
        if ($user1 && $news1) {
            DB::table('user_interactions')->insert([
                [
                    'user_id' => $user1->id,
                    'news_id' => $news1->id,
                    'interaction_type' => 'click',
                    'timestamp' => now(),
                ]
            ]);
        }

        if ($user2 && $news1) {
            DB::table('user_interactions')->insert([
                [
                    'user_id' => $user2->id,
                    'news_id' => $news1->id,
                    'interaction_type' => 'like',
                    'timestamp' => now(),
                ]
            ]);
        }

        if ($user1 && $news2) {
            DB::table('user_interactions')->insert([
                [
                    'user_id' => $user1->id,
                    'news_id' => $news2->id,
                    'interaction_type' => 'share',
                    'timestamp' => now(),
                ]
            ]);
        }

        if ($user3 && $news2) {
            DB::table('user_interactions')->insert([
                [
                    'user_id' => $user3->id,
                    'news_id' => $news2->id,
                    'interaction_type' => 'comment',
                    'timestamp' => now(),
                ]
            ]);
        }
    }
}
