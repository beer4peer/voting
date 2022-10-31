<?php

namespace App\Filament\Resources\PollResource\Pages;

use App\Filament\Resources\PollResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPoll extends EditRecord
{
    protected static string $resource = PollResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
