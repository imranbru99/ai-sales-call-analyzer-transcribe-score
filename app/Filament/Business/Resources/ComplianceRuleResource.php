<?php

namespace App\Filament\Business\Resources;

use App\Facades\Module;
use App\Models\ComplianceRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ComplianceRuleResource extends Resource
{
    protected static ?string $model = ComplianceRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Operations & Quality';

    public static function canAccess(): bool
    {
        return Module::enabled('compliance');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Compliance Rule')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\Select::make('rule_type')
                            ->options([
                                'required_phrase' => 'Required Phrase (must be said)',
                                'forbidden_phrase' => 'Forbidden Phrase (cannot be said)',
                                'mandatory_disclosure' => 'Mandatory Legal Disclosure',
                                'regulated_claim' => 'Regulated Financial / Health Claim',
                                'recording_consent' => 'Recording Consent Tracking',
                            ])
                            ->required(),
                        Forms\Components\Select::make('severity')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                                'critical' => 'Critical / Immediate Escalation',
                            ])
                            ->default('medium'),
                        Forms\Components\Select::make('industry_pack')
                            ->options([
                                'finance' => 'Financial Services & Banking',
                                'insurance' => 'Insurance Regulations',
                                'healthcare' => 'Healthcare (HIPAA)',
                                'real_estate' => 'Real Estate Fair Housing',
                                'debt_collection' => 'Debt Collection',
                            ]),
                        Forms\Components\TagsInput::make('phrases')
                            ->label('Phrases / Regex Patterns to match')
                            ->placeholder('Add phrase and press enter')
                            ->required(),
                        Forms\Components\Toggle::make('is_active')->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('rule_type')->badge()->color('info'),
                Tables\Columns\TextColumn::make('severity')->badge()->color(fn ($state) => match ($state) {
                    'critical' => 'danger',
                    'high' => 'warning',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('violations_count')->counts('violations')->label('Violations'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ComplianceRuleResource\Pages\ListComplianceRules::route('/'),
            'create' => ComplianceRuleResource\Pages\CreateComplianceRule::route('/create'),
            'edit' => ComplianceRuleResource\Pages\EditComplianceRule::route('/{record}/edit'),
        ];
    }
}
