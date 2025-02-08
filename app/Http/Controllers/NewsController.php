<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Models\User_interactions;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index()
    {
        // Ambil berita terbaru
        $berita_terbaru = News::orderBy('date', 'desc')->take(4)->get();

        // Ambil berita trending berdasarkan jumlah like dan share terbanyak
        $berita_trending = News::with('user_interactions')
            ->withCount([
                'user_interactions as total_likes' => function ($query) {
                    $query->where('interaction_type', 'like');
                },
                'user_interactions as total_shares' => function ($query) {
                    $query->where('interaction_type', 'share');
                }
            ])
            ->orderByRaw('(total_likes + total_shares) DESC')
            ->take(4)
            ->get();

        return view('home', compact('berita_terbaru', 'berita_trending'));
    }

    public function interact(Request $request)
    {
        $request->validate([
            'news_id' => 'required',
            'interaction_type' => 'required'
        ]);

        try {
            $userId = Auth::id();
            $newsId = $request->news_id;
            $interactionType = $request->interaction_type;

            // Cek apakah user sudah melakukan interaksi
            $existingInteraction = User_interactions::where('user_id', $userId)
                ->where('news_id', $newsId)
                ->where('interaction_type', $interactionType)
                ->first();

            if ($interactionType === 'like') {
                if ($existingInteraction) {
                    // Jika sudah like, maka unlike (hapus dari database)
                    $existingInteraction->delete();
                    return response()->json(['message' => 'Unliked successfully', 'liked' => false], 200);
                } else {
                    // Jika belum like, maka like (tambah ke database)
                    User_interactions::create([
                        'user_id' => $userId,
                        'news_id' => $newsId,
                        'interaction_type' => $interactionType,
                        'timestamp' => now(),
                    ]);
                    return response()->json(['message' => 'Liked successfully', 'liked' => true], 200);
                }
            }

            // Jika bukan like (misalnya share atau lainnya), hanya tambahkan ke database
            if (!$existingInteraction) {
                User_interactions::create([
                    'user_id' => $userId,
                    'news_id' => $newsId,
                    'interaction_type' => $interactionType,
                    'timestamp' => now(),
                ]);
            }

            return response()->json(['message' => 'Interaction saved successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to process interaction', 'error' => $e->getMessage()], 500);
        }
    }
}
