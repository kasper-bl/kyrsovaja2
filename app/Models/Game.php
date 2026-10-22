<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'description',
        'rules_pdf',
        'min_players',
        'max_players',
        'min_age',
        'duration_minutes',
        'difficulty',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}