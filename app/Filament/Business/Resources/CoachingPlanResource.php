<?php

namespace App\Filament\Business\Resources;

use App\Facades\Module;
use App\Models\CoachingPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CoachingPlanResource extends Resource
{
    protected static ?string $model = CoachingPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'People & Coaching';

    public static function canAccess(): bool
    {
        return Module::enabled('coaching');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Coaching Plan')
                    ->schema([
                        Forms\Components\TextInput::make('title')->required(),
                        Forms\Components\Select::make('agent_id')
                            ->relationship('agent', 'name')
                            ->required(),
                        Forms\Components\Select::make('coach_id')
                            ->relationship('coach', 'name'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'completed' => 'Completed',
                                'paused' => 'Paused',
                            ])
                            ->default('active'),
                        Forms\Components\Textarea::make('strengths')->rows(2),
                        Forms\Components\Textarea::make('gaps')->rows(2),
                        Forms\Components\DatePicker::make('start_date'),
                        Forms\Components\DatePicker::make('end_date'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('agent.name')->label('Agent')->badge()->color('info'),
                Tables\Columns\TextColumn::make('coach.name')->label('Coach'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => $state === 'active' ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('actions_count')->counts('actions')->label('Action Items'),
                Tables\Columns\TextColumn::make('start_date')->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => CoachingPlanResource\Pages\ListCoachingPlans::route('/'),
            'create' => CoachingPlanResource\Pages\CreateCoachingPlan::route('/create'),
            'edit' => CoachingPlanResource\Pages\EditCoachingPlan::route('/{record}/edit'),
        ];
    }
}
