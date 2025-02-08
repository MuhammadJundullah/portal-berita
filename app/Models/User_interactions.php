<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_interactions extends Model
{
    /** @use HasFactory<\Database\Factories\UserInteractionsFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', // Tambahkan ini
        'news_id',
        'interaction_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
