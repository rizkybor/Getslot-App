<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Seller;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    // mass assignment;
    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'address',
        'path_video',
        'price',
        'is_popular',
        'about',
        'open_time_at',
        'close_time_at',
        'category_id',
        'seller_id',
    ];

    // Ketika 1 Kategori memiliki banyak Ticket
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Ketika 1 Seller memiliki banyak Ticket
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    // Ketika 1 Tiket bisa memiliki banyak Photo
    public function photos(): HasMany
    {
        return $this->hasMany(TicketPhoto::class);
    }

    // for SEO untuk setiap data yang memiliki Slug
    public function setNameAttribute($value){
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
