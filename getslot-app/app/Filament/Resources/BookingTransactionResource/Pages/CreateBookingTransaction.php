<?php

namespace App\Filament\Resources\BookingTransactionResource\Pages;

use App\Filament\Resources\BookingTransactionResource;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;

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
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Buat BookingTransaction
        $bookingTransaction = $this->getModel()::create($data);

        // Tangani Participants
        if (!empty($this->participants)) {
            foreach ($this->participants as $participant) {
                $bookingTransaction->participants()->create($participant);
            }
        }

        return $bookingTransaction;
    }
}
