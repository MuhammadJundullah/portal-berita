<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Math\Distance\CosineSimilarity;
use Phpml\Tokenization\WhitespaceTokenizer;
use App\Models\User;
use App\Models\News;

class RecommendationController extends Controller
{
    public function recommend($userId)
    {
        // Ambil data user dan preferensinya
        $user = User::findOrFail($userId);
        $preferences = json_decode($user->preferences, true)['minat'] ?? [];

        // Ambil semua berita dari database
        $news = News::all();

        // Gabungkan preferensi user menjadi satu string
        $userPreferencesText = implode(" ", $preferences);

        // Ambil semua berita dalam bentuk teks
        $newsTexts = $news->pluck('title')->toArray();

        // Tambahkan preferensi user ke dalam dataset berita
        $dataset = array_merge([$userPreferencesText], $newsTexts);

        // Tokenisasi dan hitung TF-IDF
        $tokenizer = new WhitespaceTokenizer();
        $tfidf = new TfIdfTransformer();
        $tokens = array_map([$tokenizer, 'tokenize'], $dataset);
        $tfidf->fit($tokens);
        $tfidf->transform($tokens);

        // Hitung Cosine Similarity antara preferensi user dan berita
        $cosineSimilarity = new CosineSimilarity();
        $userVector = $tokens[0]; // Vektor preferensi user
        $newsScores = [];

        foreach ($tokens as $index => $newsVector) {
            if ($index === 0) continue; // Lewati preferensi user
            $newsScores[$index - 1] = $cosineSimilarity->distance($userVector, $newsVector);
        }

        // Urutkan berita berdasarkan similarity score (descending)
        arsort($newsScores);

        // Ambil berita yang direkomendasikan
        $recommendedNews = [];
        foreach ($newsScores as $index => $score) {
            $recommendedNews[] = [
                'title' => $news[$index]->title,
                'content' => $news[$index]->content,
                'score' => $score
            ];
        }

        return response()->json($recommendedNews);
    }
}
