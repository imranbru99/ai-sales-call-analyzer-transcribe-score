<?php

namespace App\Filament\Platform\Resources;

use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Platform Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Subscription Plan')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('price_monthly')->numeric()->prefix('$')->required(),
                        Forms\Components\TextInput::make('price_yearly')->numeric()->prefix('$')->required(),
                        Forms\Components\TextInput::make('max_agents')->numeric()->default(10)->required(),
                        Forms\Components\TextInput::make('max_minutes_per_month')->numeric()->default(2000)->required(),
                        Forms\Components\Toggle::make('is_active')->default(true),
                        Forms\Components\Textarea::make('description')->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('price_monthly')->money('USD')->label('Monthly'),
                Tables\Columns\TextColumn::make('price_yearly')->money('USD')->label('Yearly'),
                Tables\Columns\TextColumn::make('max_agents')->label('Max Seats'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => PlanResource\Pages\ListPlans::route('/'),
            'create' => PlanResource\Pages\CreatePlan::route('/create'),
            'edit' => PlanResource\Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
