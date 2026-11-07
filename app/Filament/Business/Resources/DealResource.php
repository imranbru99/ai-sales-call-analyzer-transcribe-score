<?php

namespace App\Filament\Business\Resources;

use App\Facades\Module;
use App\Models\Deal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DealResource extends Resource
{
    protected static ?string $model = Deal::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'CRM & Sales';

    public static function canAccess(): bool
    {
        return Module::enabled('crm_leads');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pipeline Deal')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\Select::make('contact_id')
                            ->relationship('contact', 'name')
                            ->searchable(),
                        Forms\Components\TextInput::make('amount')->numeric()->prefix('$')->required(),
                        Forms\Components\Select::make('stage')
                            ->options([
                                'discovery' => 'Discovery',
                                'proposal' => 'Proposal Sent',
                                'negotiation' => 'Negotiation',
                                'closed_won' => 'Closed Won',
                                'closed_lost' => 'Closed Lost',
                            ])
                            ->default('discovery'),
                        Forms\Components\TextInput::make('probability')->numeric()->suffix('%')->default(50),
                        Forms\Components\DatePicker::make('close_date'),
                        Forms\Components\Select::make('agent_id')
                            ->relationship('agent', 'name'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'open' => 'Open',
                                'won' => 'Won',
                                'lost' => 'Lost',
                            ])
                            ->default('open'),
                        Forms\Components\Textarea::make('ai_summary')->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('contact.name')->label('Contact'),
                Tables\Columns\TextColumn::make('amount')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('stage')->badge()->color('info'),
                Tables\Columns\TextColumn::make('probability')->suffix('%'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'won' => 'success',
                    'lost' => 'danger',
                    default => 'warning',
                }),
                Tables\Columns\TextColumn::make('close_date')->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => DealResource\Pages\ListDeals::route('/'),
            'create' => DealResource\Pages\CreateDeal::route('/create'),
            'edit' => DealResource\Pages\EditDeal::route('/{record}/edit'),
        ];
    }
}
