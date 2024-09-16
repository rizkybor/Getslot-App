<?php

namespace App\Filament\Resources\InitialResource\Pages;

use App\Filament\Resources\InitialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInitial extends EditRecord
{
    protected static string $resource = InitialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
