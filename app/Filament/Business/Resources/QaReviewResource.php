<?php

namespace App\Filament\Business\Resources;

use App\Facades\Module;
use App\Models\QaReview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QaReviewResource extends Resource
{
    protected static ?string $model = QaReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $navigationGroup = 'Operations & Quality';

    public static function canAccess(): bool
    {
        return Module::enabled('qa_workflow');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('QA Evaluation Review')
                    ->schema([
                        Forms\Components\Select::make('call_id')
                            ->relationship('call', 'title')
                            ->required(),
                        Forms\Components\Select::make('analyst_id')
                            ->relationship('analyst', 'name')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending Review',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'disputed' => 'Disputed by Agent',
                            ])
                            ->default('completed'),
                        Forms\Components\TextInput::make('score')
                            ->numeric()
                            ->default(85)
                            ->required(),
                        Forms\Components\Textarea::make('notes')->rows(3)->columnSpanFull(),
                        Forms\Components\Textarea::make('feedback_to_agent')->rows(3)->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('call.title')->label('Call')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('analyst.name')->label('QA Analyst')->badge(),
                Tables\Columns\TextColumn::make('score')->badge()->color(fn ($state) => $state >= 80 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => QaReviewResource\Pages\ListQaReviews::route('/'),
            'create' => QaReviewResource\Pages\CreateQaReview::route('/create'),
            'edit' => QaReviewResource\Pages\EditQaReview::route('/{record}/edit'),
        ];
    }
}
