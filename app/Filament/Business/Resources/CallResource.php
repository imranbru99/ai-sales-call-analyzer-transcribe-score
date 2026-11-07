<?php

namespace App\Filament\Business\Resources;

use App\Facades\Module;
use App\Filament\Business\Resources\CallResource\Pages;
use App\Models\Call;
use App\Models\User;
use App\Services\CallAnalysisService;
use App\Services\PdfReportService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CallResource extends Resource
{
    protected static ?string $model = Call::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationGroup = 'Call Intelligence';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return Module::enabled('call_capture') || Module::enabled('call_library');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Call Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('agent_id')
                            ->relationship('agent', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('call_type')
                            ->options([
                                'outbound' => 'Outbound Call',
                                'inbound' => 'Inbound Call',
                                'sales_demo' => 'Sales Demo',
                                'support' => 'Support Call',
                            ])
                            ->default('outbound'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'scored' => 'Scored',
                                'failed' => 'Failed',
                            ])
                            ->default('scored'),
                        Forms\Components\TextInput::make('caller_number'),
                        Forms\Components\TextInput::make('callee_number'),
                        Forms\Components\FileUpload::make('audio_path')
                            ->label('Call Audio Recording')
                            ->disk('local')
                            ->directory('calls')
                            ->acceptedFileTypes(['audio/*', 'video/*'])
                            ->maxSize(50000),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Call $record): string => ($record->agent instanceof User ? $record->agent->name : 'Rep').' • '.gmdate('i:s', $record->duration_seconds)),

                Tables\Columns\TextColumn::make('overall_score')
                    ->label('AI Score')
                    ->sortable()
                    ->badge()
                    ->color(fn (?float $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 70 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1).' / 100' : 'Pending'),

                Tables\Columns\TextColumn::make('sentiment_label')
                    ->label('Sentiment')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'positive' => 'success',
                        'negative' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('outcome')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (?string $state) => ucfirst(str_replace('_', ' ', $state ?? 'Call Ended'))),

                Tables\Columns\TextColumn::make('deal_likelihood_score')
                    ->label('Deal Likelihood')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}%" : '—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('talk_listen_ratio')
                    ->label('Talk/Listen')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}x" : '—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('sentiment_label')
                    ->label('Sentiment')
                    ->options([
                        'positive' => 'Positive',
                        'neutral' => 'Neutral',
                        'negative' => 'Negative',
                    ]),
                Tables\Filters\SelectFilter::make('outcome')
                    ->options([
                        'interested' => 'Interested',
                        'callback' => 'Callback Requested',
                        'closed_won' => 'Closed Won',
                        'not_interested' => 'Not Interested',
                    ]),
                Tables\Filters\Filter::make('high_score')
                    ->label('Top Performers (80+)')
                    ->query(fn (Builder $query): Builder => $query->where('overall_score', '>=', 80)),
                Tables\Filters\Filter::make('low_score')
                    ->label('Needs Coaching (<70)')
                    ->query(fn (Builder $query): Builder => $query->where('overall_score', '<', 70)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('pdf_scorecard')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function (Call $record, PdfReportService $pdfService) {
                        return $pdfService->generateCallScorecardPdf($record, 'en');
                    }),
                Tables\Actions\Action::make('rescore')
                    ->label('Re-Score')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (Call $record, CallAnalysisService $analysisService) {
                        $analysisService->analyze($record);
                        Notification::make()
                            ->title('Call Re-Scored Successfully')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalls::route('/'),
            'create' => Pages\CreateCall::route('/create'),
            'view' => Pages\ViewCall::route('/{record}'),
        ];
    }
}
