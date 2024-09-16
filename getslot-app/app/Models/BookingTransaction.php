<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ticket;

class BookingTransaction extends Model
{
    use HasFactory, SoftDeletes;

    // mass assignment;
    protected $fillable = [
        'name',
        'booking_trx_id',
        'phone_number',
        'email',
        'proof',
        'total_amount',
        'total_participant',
        'is_paid',
        'started_at',
        'ticket_id',
    ];

    protected $casts = [
        'started_at' => 'date',
    ];

    public static function generateSequentialTrxId()
{
    // Dapatkan ID terakhir
    $lastTransaction = self::withTrashed()->latest('id')->first();
    $prefix = 'GS-';
    
    // Jika belum ada, mulai dari 000001
    if (!$lastTransaction) {
        $nextId = 1;
    } else {
        // Ambil ID terakhir, tambah 1
        $nextId = $lastTransaction->id + 1;
    }
    $formattedId = str_pad($nextId, 4, '0', STR_PAD_LEFT);

    // Gabungkan prefix dengan formattedId
    return $prefix . $formattedId;
}

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function bookingParticipant(): HasMany
    {
        return $this->hasMany(BookingParticipant::class);
    }
}
