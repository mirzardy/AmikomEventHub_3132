<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'title', 
        'description', 
        'date',
        'location', 
        'price', 
        'stock', 
        'poster_path'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function getPosterUrlAttribute()
    {
        if (! $this->poster_path) {
            return 'https://placehold.co/200x600';
        }

        if (Storage::disk('public')->exists($this->poster_path)) {
            return asset('storage/' . $this->poster_path);
        }

        if (file_exists(public_path($this->poster_path))) {
            return asset($this->poster_path);
        }

        return 'https://placehold.co/200x600';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}