<?php

namespace App\Filament\Business\Widgets;

use App\Models\Call;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopObjectionsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Call::query()->latest()->limit(5))
            ->heading('Recent High-Impact Call Intelligence')
            ->columns([
                Tables\Columns\TextColumn::make('title')->weight('bold'),
                Tables\Columns\TextColumn::make('agent.name')->label('Rep')->badge(),
                Tables\Columns\TextColumn::make('overall_score')
                    ->label('Score')
                    ->badge()
                    ->color(fn ($state) => $state >= 80 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('sentiment_label')->label('Sentiment')->badge(),
                Tables\Columns\TextColumn::make('outcome')->badge()->color('info'),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label('Review')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Call $record): string => "/app/calls/{$record->id}"),
            ]);
    }
}
