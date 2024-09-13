<?php

namespace App\Filament\Resources\BookingTransactionResource\Pages;

use Filament\Actions;
use App\Models\Initial;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\BookingTransactionResource;

class CreateBookingTransaction extends CreateRecord
{
    protected static string $resource = BookingTransactionResource::class;

    // Properti untuk menyimpan data participants
    protected array $participants = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Simpan participants dalam properti kelas dan hapus dari $data
        $this->participants = $data['participants'] ?? [];
        unset($data['participants']);

        // Validasi setiap participant untuk memastikan `initial_id` ada
        foreach ($this->participants as $participant) {
            if (empty($participant['initial_id'])) {
                throw new \Exception('Initial ID is required for each participant.');
            }
        }

        // Hitung total_amount berdasarkan participants
        $data['total_amount'] = collect($this->participants)->sum(function ($participant) {
            $initial = Initial::find($participant['initial_id']);
            return $initial ? $initial->price : 0;
        });

        // Pastikan total_amount ada sebelum melakukan insert
        if ($data['total_amount'] === 0) {
            throw new \Exception('Total amount cannot be zero.');
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
{
    // Buat BookingTransaction
    $bookingTransaction = $this->getModel()::create($data);

    // Tangani Participants
    if (!empty($this->participants)) {
        foreach ($this->participants as $participant) {
            // Pastikan `initial_id` ada dalam data participant
            if (!isset($participant['initial_id']) || empty($participant['initial_id'])) {
                throw new \Exception('Initial ID is required for each participant.');
            }

            // Simpan participant dengan initial_id yang valid
            $bookingTransaction->participants()->create($participant);
        }
    }

    return $bookingTransaction;
}
}