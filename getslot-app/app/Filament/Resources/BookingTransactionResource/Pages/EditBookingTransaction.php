<?php

namespace App\Filament\Resources\BookingTransactionResource\Pages;

use Filament\Actions;
use App\Models\Initial;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\BookingTransactionResource;

class EditBookingTransaction extends EditRecord
{
    protected static string $resource = BookingTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected array $participants = [];

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Simpan participants dalam properti kelas dan hapus dari $data
        $this->participants = $data['participants'] ?? [];
        unset($data['participants']);

        // Hitung total_amount berdasarkan participants
        $data['total_amount'] = collect($this->participants)->sum(function ($participant) {
            $initial = Initial::find($participant['initial_id']);
            return $initial ? $initial->price : 0;
        });

        // Pastikan total_amount ada sebelum melakukan update
        if ($data['total_amount'] === 0) {
            throw new \Exception('Total amount cannot be zero.');
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Update the BookingTransaction
        $record->update($data);

        // Tangani Participants
        $existingParticipants = $record->participants()->pluck('id')->toArray();
        $submittedParticipantIds = collect($this->participants)->pluck('id')->filter()->toArray();

        // Hapus participants yang tidak ada dalam data yang di-submit
        $participantsToDelete = array_diff($existingParticipants, $submittedParticipantIds);
        if (!empty($participantsToDelete)) {
            $record->participants()->whereIn('id', $participantsToDelete)->delete();
        }

        // Simpan atau update participants
        foreach ($this->participants as $participant) {
            if (isset($participant['id']) && in_array($participant['id'], $submittedParticipantIds)) {
                // Update existing participant
                $record->participants()->where('id', $participant['id'])->update($participant);
            } else {
                // Create new participant
                $record->participants()->create($participant);
            }
        }

        return $record;
    }
}