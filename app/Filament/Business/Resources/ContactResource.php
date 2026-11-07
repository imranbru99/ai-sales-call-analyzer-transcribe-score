<?php

namespace App\Filament\Business\Resources;

use App\Facades\Module;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

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
                Forms\Components\Section::make('Contact Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('email')->email(),
                        Forms\Components\TextInput::make('phone'),
                        Forms\Components\TextInput::make('company_name'),
                        Forms\Components\TextInput::make('job_title'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'lead' => 'New Lead',
                                'prospect' => 'Qualified Prospect',
                                'customer' => 'Closed Customer',
                                'churned' => 'Churned',
                            ])
                            ->default('lead'),
                        Forms\Components\TextInput::make('lead_score')->numeric()->default(65),
                        Forms\Components\Select::make('assigned_agent_id')
                            ->relationship('agent', 'name'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('company_name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'customer' => 'success',
                    'prospect' => 'info',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('lead_score')->label('Score')->badge(),
                Tables\Columns\TextColumn::make('agent.name')->label('Assigned Agent'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ContactResource\Pages\ListContacts::route('/'),
            'create' => ContactResource\Pages\CreateContact::route('/create'),
            'edit' => ContactResource\Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
