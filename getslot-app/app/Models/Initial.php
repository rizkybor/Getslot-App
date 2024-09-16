<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Initial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'price'
    ];

    public function type(): BelongsToMany
    {
        return $this->belongsToMany(Type::class, 'initial_types', 'initial_id', 'type_id');
    }

    public function setNameAttribute($value){
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
