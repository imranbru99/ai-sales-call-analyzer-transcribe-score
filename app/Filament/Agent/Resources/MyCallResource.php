<?php

namespace App\Filament\Agent\Resources;

use App\Models\Call;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MyCallResource extends Resource
{
    protected static ?string $model = Call::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationLabel = 'My Calls';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('agent_id', auth()->id());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Call $record): string => gmdate('i:s', $record->duration_seconds).' • '.($record->caller_number ?? 'Lead')),

                Tables\Columns\TextColumn::make('overall_score')
                    ->label('Score')
                    ->badge()
                    ->color(fn (?float $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 70 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1).' / 100' : 'Pending'),

                Tables\Columns\TextColumn::make('sentiment_label')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'positive' => 'success',
                        'negative' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('outcome')->badge()->color('info'),
                Tables\Columns\TextColumn::make('talk_listen_ratio')->label('Talk Ratio')->suffix('x'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('M d, Y H:i')->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_details')
                    ->label('Review Call')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Call $record): string => "/app/calls/{$record->id}"),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => MyCallResource\Pages\ListMyCalls::route('/'),
        ];
    }
}
