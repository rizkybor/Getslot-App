<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingParticipant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_transaction_id',
        'participant_name',
        'identity_user',
        'contingen',
        'initial_id',
        'type_id'
    ];
}
