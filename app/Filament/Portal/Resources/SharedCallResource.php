<?php

namespace App\Filament\Portal\Resources;

use App\Models\Call;
use App\Models\CsatSurvey;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SharedCallResource extends Resource
{
    protected static ?string $model = Call::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone-arrow-down-left';

    protected static ?string $navigationLabel = 'Shared Meeting Summaries';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->weight('bold'),
                Tables\Columns\TextColumn::make('created_at')->label('Meeting Date')->date(),
                Tables\Columns\TextColumn::make('duration_seconds')->formatStateUsing(fn ($s) => gmdate('i:s', $s)),
                Tables\Columns\TextColumn::make('executive_summary')->label('Key Action Items')->limit(75),
            ])
            ->actions([
                Tables\Actions\Action::make('give_feedback')
                    ->label('Submit CSAT')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->form([
                        Select::make('rating')
                            ->label('How satisfied were you with this call?')
                            ->options([
                                5 => '⭐⭐⭐⭐⭐ Excellent',
                                4 => '⭐⭐⭐⭐ Good',
                                3 => '⭐⭐⭐ Average',
                                2 => '⭐⭐ Poor',
                                1 => '⭐ Terrible',
                            ])
                            ->default(5)
                            ->required(),
                        Textarea::make('feedback')
                            ->label('Optional Comments'),
                    ])
                    ->action(function (Call $record, array $data) {
                        CsatSurvey::create([
                            'call_id' => $record->id,
                            'tenant_id' => $record->tenant_id,
                            'score' => $data['rating'],
                            'feedback' => $data['feedback'],
                            'channel' => 'portal',
                            'responded_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Feedback Received')
                            ->body('Thank you for rating your experience!')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => SharedCallResource\Pages\ListSharedCalls::route('/'),
        ];
    }
}
