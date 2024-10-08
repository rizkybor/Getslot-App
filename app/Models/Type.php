<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Type extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'icon',
        'initial_id'
    ];

    public function initial(): BelongsToMany
    {
        return $this->belongsToMany(Initial::class, 'initial_types', 'type_id', 'initial_id')->withPivot('created_at', 'updated_at');
    }

    public function ticket(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'ticket_types');
    }

    public function setNameAttribute($value){
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
