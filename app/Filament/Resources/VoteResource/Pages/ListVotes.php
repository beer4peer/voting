<?php

namespace App\Filament\Resources\VoteResource\Pages;

use App\Filament\Resources\VoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVotes extends ListRecords
{
    protected static string $resource = VoteResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
