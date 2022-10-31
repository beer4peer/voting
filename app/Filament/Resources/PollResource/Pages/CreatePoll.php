<?php

namespace App\Filament\Resources\PollResource\Pages;

use App\Filament\Resources\PollResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePoll extends CreateRecord
{
    protected static string $resource = PollResource::class;
}
