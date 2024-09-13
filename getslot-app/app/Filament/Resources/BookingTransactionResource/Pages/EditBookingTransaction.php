<?php

namespace App\Filament\Resources\BookingTransactionResource\Pages;

use App\Filament\Resources\BookingTransactionResource;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;

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
