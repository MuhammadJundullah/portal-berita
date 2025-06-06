<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);


test('guest user sees basic news without recommendations', function () {
    // Clear cache to ensure fresh requests
    Cache::flush();

    // Mock API response with all required fields that the view expects
    $mockNews = [
        [
            'title' => 'Test News',
            'description' => 'Test Description',
            'url' => 'https://example.com/test-news', // Added url field
            'urlToImage' => 'test.jpg',
            'publishedAt' => now()->toIso8601String(),
            'author' => 'Test Author',
            'source' => ['name' => 'Test Source'], // Added source.name field if needed
            'content' => 'Test content' // Added content field if needed
        ]
    ];

    // Mock both API endpoints
    Http::fake([
        'newsapi.org/v2/everything*' => Http::response([
            'status' => 'ok',
            'articles' => $mockNews
        ], 200),
        'newsapi.org/v2/top-headlines*' => Http::response([
            'status' => 'ok',
            'articles' => $mockNews
        ], 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertViewIs('home')
        ->assertViewHas('berita_terbaru', $mockNews)
        ->assertViewHas('berita_trending', $mockNews)
        ->assertViewHas('berita_rekomendasi', [])
        ->assertViewHas('userAge', 0);

    // Verify cache was set
    $this->assertTrue(Cache::has('news_latest'));
    $this->assertTrue(Cache::has('news_trending'));
});

test('authenticated user sees cached news', function () {
    // Freeze time to ensure consistent age calculation
    $this->travelTo(now());

    $user = User::factory()->create([
        'created_at' => now()->subDays(11)->startOfDay(),
        'preferences' => 'technology,science'
    ]);

    // Create complete cached news data
    $cachedNews = [
        [
            'title' => 'Cached News',
            'description' => 'Cached Description',
            'url' => 'https://example.com/cached-news',
            'urlToImage' => 'cache.jpg',
            'publishedAt' => now()->toIso8601String(),
            'author' => 'Cached Author',
            'source' => ['name' => 'Cached Source'],
            'content' => 'Cached content'
        ]
    ];

    $cachedRecommendations = [
        [
            'title' => 'Recommended News',
            'description' => 'Recommended Description',
            'url' => 'https://example.com/recommended-news',
            'publishedAt' => now()->toIso8601String(),
            'source' => ['name' => 'Recommended Source']
        ]
    ];

    // Mock cache responses
    Cache::shouldReceive('has')
        ->with('news_latest')
        ->once()
        ->andReturn(true);

    Cache::shouldReceive('get')
        ->with('news_latest')
        ->once()
        ->andReturn($cachedNews);

    Cache::shouldReceive('has')
        ->with('news_trending')
        ->once()
        ->andReturn(true);

    Cache::shouldReceive('get')
        ->with('news_trending')
        ->once()
        ->andReturn($cachedNews);

    Cache::shouldReceive('has')
        ->with('news_recommendation_' . $user->id)
        ->once()
        ->andReturn(true);

    Cache::shouldReceive('get')
        ->with('news_recommendation_' . $user->id)
        ->once()
        ->andReturn($cachedRecommendations);

    $response = $this->actingAs($user)
        ->get(route('home'));

    $response->assertOk()
        ->assertViewIs('home')
        ->assertViewHas('berita_terbaru', $cachedNews)
        ->assertViewHas('berita_trending', $cachedNews)
        ->assertViewHas('berita_rekomendasi', $cachedRecommendations);
       
    });

test('new user (<7 days) doesnt see recommendations', function () {
    $user = User::factory()->create(['created_at' => now()->subDays(3)]);

    $this->actingAs($user)
        ->get('/')
        ->assertViewHas('berita_rekomendasi', []);
});

test('mature user (≥7 days) gets personalized recommendations', function () {
    $user = User::factory()->create([
        'created_at' => now()->subDays(10),
        'preferences' => 'technology'
    ]);

    // Mock recommendation API
    Http::fake([
        'api-recomendation-news-production.up.railway.app/recommend_news' =>
        Http::response(['recommended_tech_news'], 200)
    ]);

    $this->actingAs($user)
        ->get('/')
        ->assertViewHas('berita_rekomendasi', ['recommended_tech_news']);
});

test('failed API calls return empty arrays', function () {
    Http::fake([
        'newsapi.org/*' => Http::response([], 500),
    ]);

    $response = $this->get('/');

    $response->assertViewHasAll([
        'berita_terbaru' => [],
        'berita_trending' => []
    ]);
});
test('cache is populated on first request', function () {
    // Clear cache pertama
    Cache::flush();

    Http::fake([
        'newsapi.org/v2/everything*' => Http::response(['articles' => ['new']], 200),
    ]);

    // Mock hanya bagian 'has' untuk return false
    Cache::shouldReceive('has')
        ->with('news_latest')
        ->once()
        ->andReturn(false);

    // Mock put untuk cache baru
    Cache::shouldReceive('put')
        ->with('news_latest', ['new'], \Mockery::type('DateTime'))
        ->once();

    $this->get(route('home'));
});
