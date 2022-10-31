<?php

namespace App\Filament\Resources\PollResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VotesRelationManager extends RelationManager
{
    protected static string $relationship = 'votes';
    protected static ?string $recordTitleAttribute = 'user.name';

    protected function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
            ]);
    }
}
