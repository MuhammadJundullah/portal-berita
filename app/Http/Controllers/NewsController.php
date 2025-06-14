<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use jcobhams\NewsApi\NewsApi;
use Illuminate\Support\Carbon;
use App\Models\User_interactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    public function index()
    {
        $cacheTime = now()->addMinutes(120);
        $apiKey = env('API_KEY');
        $user = Auth::user();
        $userAge = $user ? Carbon::parse($user->created_at)->diffInDays(now()) : 0;

        // Fetch berita terbaru dari News API
        $cacheKeyLatest = 'news_latest';
        if (Cache::has($cacheKeyLatest)) {
            $berita_terbaru = Cache::get($cacheKeyLatest);
        } else {
            $responseLatest = Http::get("https://newsapi.org/v2/everything", [
                'q' => 'today',
                'language' => 'en',
                'sortBy' => 'publishedAt',
                'pageSize' => 20,
                'apiKey' => $apiKey
            ]);
            $berita_terbaru = $responseLatest->successful() ? $responseLatest->json()['articles'] : [];
            Cache::put($cacheKeyLatest, $berita_terbaru, $cacheTime);
        }

        // Fetch berita trending dari News API
        $cacheKeyTrending = 'news_trending';
        if (Cache::has($cacheKeyTrending)) {
            $berita_trending = Cache::get($cacheKeyTrending);
        } else {
            $responseTrending = Http::get("https://newsapi.org/v2/top-headlines", [
                'language' => 'en',
                'pageSize' => 20,
                'apiKey' => $apiKey
            ]);
            $berita_trending = $responseTrending->successful() ? $responseTrending->json()['articles'] : [];
            Cache::put($cacheKeyTrending, $berita_trending, $cacheTime);
        }

        // Jika akun user lebih dari 7 hari, gunakan rekomendasi berita ini
        $berita_rekomendasi = [];
        if ($user && $userAge >= 7) {
            $cacheKeyRecommendation = 'news_recommendation_' . $user->id;
            if (Cache::has($cacheKeyRecommendation)) {
                $berita_rekomendasi = Cache::get($cacheKeyRecommendation);
            } else {
                $preferences = $user->preferences;
                $userInteractions = User_interactions::where('user_id', $user->id)->pluck('news_title');

                $response = Http::post('https://api-recomendation-news-production.up.railway.app/recommend_news', [
                    'preferences' => $preferences,
                    'interactions' => $userInteractions->toArray(),
                ]);

                if ($response->successful()) {
                    $berita_rekomendasi = $response->json();
                    Cache::put($cacheKeyRecommendation, $berita_rekomendasi, $cacheTime);
                }
            }
        }

        return view('home', compact('berita_terbaru', 'berita_trending', 'berita_rekomendasi', 'userAge'));
    }

    public function news($params, Request $request)
    {
        $page = $request->query('page', 1);
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

    // Untuk mendapatkan informasi intearaksi user
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
