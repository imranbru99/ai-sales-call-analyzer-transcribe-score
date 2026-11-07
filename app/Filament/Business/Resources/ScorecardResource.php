<?php

namespace App\Filament\Business\Resources;

use App\Facades\Module;
use App\Models\Scorecard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScorecardResource extends Resource
{
    protected static ?string $model = Scorecard::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Call Intelligence';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return Module::enabled('ai_scoring');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Scorecard Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('call_type')
                            ->options([
                                'cold_call' => 'Cold Outbound Call',
                                'sales_demo' => 'Sales Demo & Pitch',
                                'follow_up' => 'Pipeline Follow-up',
                                'support' => 'Support / Service',
                                'collections' => 'Debt Collections',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('pass_threshold')
                            ->label('Passing Score (%)')
                            ->numeric()
                            ->default(70)
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Evaluation Criteria & Weights')
                    ->schema([
                        Forms\Components\Repeater::make('criteria')
                            ->schema([
                                Forms\Components\TextInput::make('key')->required(),
                                Forms\Components\TextInput::make('title')->required(),
                                Forms\Components\TextInput::make('weight')
                                    ->numeric()
                                    ->default(15)
                                    ->required(),
                                Forms\Components\Toggle::make('is_critical')
                                    ->label('Critical Fail (auto-zero call score if failed)'),
                                Forms\Components\TextInput::make('description'),
                            ])
                            ->columns(2)
                            ->default([
                                ['key' => 'greeting', 'title' => 'Greeting & Introduction', 'weight' => 10, 'is_critical' => false],
                                ['key' => 'discovery', 'title' => 'Discovery & Need Analysis', 'weight' => 20, 'is_critical' => false],
                                ['key' => 'active_listening', 'title' => 'Active Listening & Empathy', 'weight' => 15, 'is_critical' => false],
                                ['key' => 'objection_handling', 'title' => 'Objection Handling', 'weight' => 20, 'is_critical' => false],
                                ['key' => 'pitch_quality', 'title' => 'Solution Pitch Quality', 'weight' => 15, 'is_critical' => false],
                                ['key' => 'compliance', 'title' => 'Regulatory & Script Compliance', 'weight' => 10, 'is_critical' => true],
                                ['key' => 'closing', 'title' => 'Closing & Next Step Setting', 'weight' => 10, 'is_critical' => false],
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('call_type')->badge()->color('info'),
                Tables\Columns\TextColumn::make('pass_threshold')->suffix('%')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ScorecardResource\Pages\ListScorecards::route('/'),
            'create' => ScorecardResource\Pages\CreateScorecard::route('/create'),
            'edit' => ScorecardResource\Pages\EditScorecard::route('/{record}/edit'),
        ];
    }
}
