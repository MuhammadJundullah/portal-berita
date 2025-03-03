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

    // old news query from local database
    // public function index()
    // {
    //     // Ambil berita terbaru
    //     $berita_terbaru = News::orderBy('created_at', 'asc')->take(15)->get();

    //     // Ambil berita trending berdasarkan jumlah like dan share terbanyak
    //     $berita_trending = News::with('user_interactions')
    //         ->withCount([
    //             'user_interactions as total_likes' => function ($query) {
    //                 $query->where('interaction_type', 'like');
    //             },
    //             'user_interactions as total_shares' => function ($query) {
    //                 $query->where('interaction_type', 'share');
    //             }
    //         ])
    //         ->orderByRaw('(total_likes + total_shares) DESC')
    //         ->paginate(10);

    //     return view('home', compact('berita_terbaru', 'berita_trending'));
    // }

    public function index()
    {
        $cacheKeyLatest = 'news_latest';
        $cacheKeyTrending = 'news_trending_' . request('page', 1); // Cache per halaman
        $cacheTime = now()->addMinutes(120);
        $apiKey = env('API_KEY');

        if (Cache::has($cacheKeyLatest)) {
            $berita_terbaru = Cache::get($cacheKeyLatest);
        } else {
            $responseLatest = Http::get("https://newsapi.org/v2/everything", [
                'q' => 'today',
                'language' => 'en',
                'sortBy' => 'publishedAt',
                'pageSize' => 20,
                'page' => request('page', 1),
                'apiKey' => $apiKey
            ]);

            $berita_terbaru = $responseLatest->successful() ? $responseLatest->json()['articles'] : [];
            Cache::put($cacheKeyLatest, $berita_terbaru, $cacheTime);
        }

        if (Cache::has($cacheKeyTrending)) {
            $berita_trending = Cache::get($cacheKeyTrending);
        } else {
            $responseTrending = Http::get("https://newsapi.org/v2/top-headlines", [
                'q' => null,
                'language' => 'en',
                'pageSize' => 20,
                'page' => request('page', 1),
                'apiKey' => $apiKey
            ]);

            $berita_trending = $responseTrending->successful() ? $responseTrending->json()['articles'] : [];
            Cache::put($cacheKeyTrending, $berita_trending, $cacheTime);
        }

        return view('home', compact('berita_terbaru', 'berita_trending'));
    }

    public function news($params, Request $request)
    {
        $page = $request->query('page', 1); // Ambil page dari URL, default 1
        $cacheKey = "news_{$params}_page_{$page}";
        $cacheTime = now()->addMinutes(120);
        $apiKey = env('API_KEY');

        if (Cache::has($cacheKey)) {
            $berita = Cache::get($cacheKey);
        } else {
            $endpoint = ($params === 'trending') ? "top-headlines" : "everything";

            $queryParams = [
                'language' => 'en',
                'pageSize' => 20,
                'page' => $page,
                'apiKey' => $apiKey
            ];

            if ($params === 'newest') {
                $queryParams['q'] = 'today';
                $queryParams['sortBy'] = 'publishedAt';
            }

            $response = Http::get("https://newsapi.org/v2/{$endpoint}", $queryParams);
            $berita = $response->successful() ? $response->json()['articles'] : [];

            Cache::put($cacheKey, $berita, $cacheTime);
        }

        return view('other_news', compact(
            'berita',
            'params',
            'page'
        ));
    }

    // for get data user interaction in home page
    public function interact(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            // Validasi request
            $validated = $request->validate([
                'news_title' => 'required|string',
                'interaction_type' => 'required|in:like,share',
            ]);

            if ($validated['interaction_type'] === 'like') {
                // Cek apakah user sudah like sebelumnya
                $existingInteraction = User_interactions::where('user_id', Auth::id())
                ->where('news_title', $validated['news_title'])
                ->where('interaction_type', 'like')
                ->first();

                if ($existingInteraction) {
                    // Jika sudah like, hapus dari database (unlike)
                    $existingInteraction->delete();
                    return response()->json(['message' => 'Like removed successfully!', 'status' => 'unliked']);
                } else {
                    // Jika belum, tambahkan like baru
                    User_interactions::create([
                        'news_title' => $validated['news_title'],
                        'interaction_type' => 'like',
                        'user_id' => Auth::id(),
                    ]);
                    return response()->json(['message' => 'Like saved successfully!', 'status' => 'liked']);
                }
            } elseif ($validated['interaction_type'] === 'share') {
                // Simpan share tanpa bisa dihapus
                User_interactions::create([
                    'news_title' => $validated['news_title'],
                    'interaction_type' => 'share',
                    'user_id' => Auth::id(),
                ]);
                return response()->json(['message' => 'Post shared successfully!', 'status' => 'shared']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // search old from local database
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
        $newsapi = new NewsApi(env('API_KEY'));

        $q = $request->input('query');
        $sources = null;
        $domains = null;
        $exclude_domains = null;
        $from = null;
        $to = null;
        $language = 'en';
        $today = now()->toDateString();
        $sort_by = 'publishedAt';
        $page_size = 12;
        $page = $request->input('page', 1);

        if (!$q) {
            return view('search', ['news' => null]);
        }

        $cacheKey = 'search_' . md5($q . $page);

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

            if (!isset($news['articles'])) {
                return redirect()->back()->with(
                    'error',
                    'Failed to fetch news'
                );
            }

            Cache::put($cacheKey, $news, now()->addMinutes(60));
        }

        return view('search', compact('news', 'q'));
    }

    public function other_news()
    {
        $cacheKey = 'news_wsj';
        $cacheTime = now()->addMinutes(60);

        if (Cache::has($cacheKey)) {
            $all_articles = Cache::get($cacheKey);
        } else {
            $newsapi = new NewsApi(env('API_KEY'));

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

            if (!isset($all_articles['articles'])) {
                return redirect()->back()->with('error', 'Failed to fetch news');
            }
            
            Cache::put($cacheKey, $all_articles, $cacheTime);
        }

        return view('other_news', compact('all_articles'));
    }
}
