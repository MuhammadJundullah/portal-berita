<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use jcobhams\NewsApi\NewsApi;
use Illuminate\Support\Carbon;
use Phpml\Math\Distance\Cosine;
use App\Models\User_interactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Phpml\FeatureExtraction\TfIdfTransformer;

class NewsController extends Controller
{
    private function recommendNews($user)
    {
        $userPreferences = json_decode($user->preferences, true);
        if (!$userPreferences) {
            return [];
        }

        $cacheKey = 'news_recommendation_' . request('page', 1);
        $cacheTime = now()->addMinutes(120);
        $apiKey = env('API_KEY');

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = Http::get("https://newsapi.org/v2/everything", [
            'q' => implode(' OR ', $userPreferences),
            'language' => 'en',
            'sortBy' => 'publishedAt',
            'pageSize' => 50,
            'page' => request('page', 1),
            'apiKey' => $apiKey
        ]);

        $newsList = $response->successful() ? $response->json()['articles'] : [];

        if (count($newsList) > 10) {
            $newsList = $this->filterTopNews($newsList, $userPreferences);
        }

        Cache::put($cacheKey, $newsList, $cacheTime);
        return $newsList;
    }

    private function filterTopNews($newsList, $userPreferences)
    {
        if (empty($newsList) || empty($userPreferences)) {
            return [];
        }

        $tfidf = new TfidfTransformer();
        $cosine = new Cosine();

        // Konversi berita ke array kata (judul + deskripsi)
        $newsTexts = array_map(
            function ($news) {
                $text = strtolower(($news['title'] ?? '') . ' ' . ($news['description'] ?? ''));
                return array_values(array_filter(explode(' ', $text)));
            },
            $newsList
        );

        // Konversi preferensi user ke array kata
        $userPrefText = array_values(array_filter(explode(' ', strtolower(implode(' ', $userPreferences ?? [])))));

        // Gabungkan user preferences dengan berita
        $corpus = array_merge([$userPrefText], $newsTexts);

        array_shift($corpus);
        $corpus = array_values($corpus);

        // Pastikan corpus memiliki minimal 2 dokumen sebelum transformasi
        dd(count($newsList), count($corpus), $newsList, $corpus);

        $tfidf->fit($corpus);
        $tfidf->transform($corpus);

        // Ambil vektor user setelah transformasi
        $userVector = array_shift($corpus);

        if (empty($userVector)) {
            dd("Error: userVector kosong setelah array_shift()", $userVector, $corpus);
        }

        // Reset indeks corpus agar sesuai dengan newsList
        $corpus = array_values($corpus);

        // Debug: Periksa kesesuaian panjang array
        if (count($corpus) !== count($newsList)) {
            dd("Error: Panjang corpus tidak sesuai dengan newsList!", count($corpus), count($newsList), $corpus, $newsList);
        }

        $scores = [];

        foreach ($corpus as $index => $newsVector) {
            // Pastikan indeks ada sebelum mengakses newsList
            if (!isset($newsList[$index])) {
                dd("Error: Index $index tidak ada di newsList!", $index, $newsList);
            }

            $scores[] = [
                'news' => $newsList[$index],
                'similarity' => 1 - $cosine->distance($userVector, $newsVector)
            ];
        }

        // Urutkan berdasarkan similarity score tertinggi
        usort($scores, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

        return array_slice(array_column($scores, 'news'), 0, 10);
    }

    public function index()
    {
        $cacheKeyLatest = 'news_latest';
        $cacheKeyTrending = 'news_trending_' . request('page', 1);
        $cacheTime = now()->addMinutes(120);
        $apiKey = env('API_KEY');

        // Cek apakah user login dan sudah lebih dari 5 hari
        $user = Auth::user();
        $isNewUser = !$user || Carbon::parse($user->created_at)->diffInDays(now()) < 5;

        // Ambil berita terbaru
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

        if ($isNewUser) {
            // Jika user baru (<5 hari) atau belum login, tampilkan berita trending
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
        } else {
            // Jika user sudah > 5 hari, tampilkan berita rekomendasi
            $berita_rekomendasi = $this->recommendNews($user);
            return view('home', compact('berita_terbaru', 'berita_rekomendasi'));
        }
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
