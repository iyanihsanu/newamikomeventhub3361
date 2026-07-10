<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'category_id', 'title', 'description', 'image', 
        'location', 'event_date', 'price', 'stock'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Untuk mengecek jumlah keranjang yang terkait dengan event ini
    public function carts() 
    {
        return $this->hasMany(Cart::class);
    }
}