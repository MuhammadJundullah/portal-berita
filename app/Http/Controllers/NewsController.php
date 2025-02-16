<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use jcobhams\NewsApi\NewsApi;
use App\Models\User_interactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    // public function index()
    // {
    //     $berita_terbaru = News::orderBy('date', 'desc')->take(15)->get();

    //     $apiKey = "4adbefa5122d420d92fa2f1066a41b7e";
    //     $url = "https://newsapi.org/v2/everything?q=tesla&from=2025-01-15&sortBy=publishedAt&apiKey={$apiKey}";

    //     $response = Http::get($url);
    //     $berita_trending = $response->json()['articles'] ?? [];
    //     dd($berita_trending);

    //     return view('home', compact('berita_terbaru', 'berita_trending'));
    // }

    public function index()
    {
        // Ambil berita terbaru
        $berita_terbaru = News::orderBy('created_at', 'asc')->take(15)->get();

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
            ->paginate(10);

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

    // public function search(Request $request)
    // {
    //     $query = $request->input('query'); // Ambil input pencarian dari user

    //     $news = News::when($query, function ($q) use ($query) {
    //         $q->where('headline', 'like', "%$query%")
    //             ->orWhere('short_description', 'like', "%$query%");
    //     })
    //         ->orderBy('date', 'desc')
    //         ->take(15)
    //         ->get();

    //     return view('search', compact('news', 'query'));
    // }

    public function search(Request $request)
    {
        $newsapi = new NewsApi('7c5e9c08427f462bb9ddb8b1a7737485');

        $q = $request->input('query');
        $sources = null;
        $domains = null;
        $exclude_domains = null;
        $from = null;
        $to = null;
        $language = 'en';
        $today = now()->toDateString();
        $sort_by = 'publishedAt';
        $page_size = 10;
        $page = $request->input('page', 1);

        if (!$q) {
            return view('search', ['news' => null]);
        }

        // Buat cache key unik berdasarkan query & halaman
        $cacheKey = 'search_' . md5($q . $page);

        // Cek apakah hasil pencarian sudah ada di cache
        if (Cache::has($cacheKey)) {
            $news = Cache::get($cacheKey);
        } else {
            $news = $newsapi->getEverything(
                $q,
                $sources,
                $domains,
                $exclude_domains,
                $from,
                $to,
                $language,
                $sort_by,
                $page_size,
                $page
            );

            $news = json_decode(json_encode($news), true);

            // Cek apakah response API berhasil
            if (!isset($news['articles'])) {
                return redirect()->back()->with(
                    'error',
                    'Failed to fetch news'
                );
            }

            // Simpan hasil pencarian ke cache selama 15 menit
            Cache::put($cacheKey, $news, now()->addMinutes(15));
        }

        return view('search', compact('news', 'q'));
    }

    public function other_news()
    {
        $cacheKey = 'news_wsj'; // Nama cache key
        $cacheTime = now()->addMinutes(30); // Cache selama 30 menit

        // Cek apakah data sudah ada di cache
        if (Cache::has($cacheKey)) {
            $all_articles = Cache::get($cacheKey);
        } else {
            $newsapi = new NewsApi('7c5e9c08427f462bb9ddb8b1a7737485');

            $q = null;
            $sources = null;
            $domains = 'wsj.com';
            $exclude_domains = null;
            $from = null;
            $to = null;
            $language = 'en';
            $today = now()->toDateString();
            $sort_by = 'publishedAt';
            $page_size = 10;
            $page = 1;

            $all_articles = $newsapi->getEverything(
                $q,
                $sources,
                $domains,
                $exclude_domains,
                $from,
                $to,
                $language,
                $sort_by,
                $page_size,
                $page
            );

            $all_articles = json_decode(
                json_encode($all_articles),
                true
            );

            // Cek apakah response API berhasil
            if (!isset($all_articles['articles'])) {
                return redirect()->back()->with('error', 'Failed to fetch news');
            }

            // Simpan ke cache
            Cache::put($cacheKey, $all_articles, $cacheTime);
        }

        return view('other_news', compact('all_articles'));
    }
}
