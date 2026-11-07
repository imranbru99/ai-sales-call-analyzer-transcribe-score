<?php

namespace App\Filament\Platform\Resources;

use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Platform Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Tenant Organization')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('domain')->unique(ignoreRecord: true),
                        Forms\Components\Select::make('business_type')
                            ->options([
                                'call_center' => 'Call Center & BPO',
                                'saas_sales' => 'SaaS Sales Team',
                                'real_estate' => 'Real Estate',
                                'insurance' => 'Insurance & Finance',
                                'solo_freelancer' => 'Solo Freelancer',
                            ])
                            ->default('call_center')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'trial' => 'Trial Period',
                                'suspended' => 'Suspended',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('slug')->badge(),
                Tables\Columns\TextColumn::make('business_type')->badge()->color('info'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state) {
                    'active' => 'success',
                    'trial' => 'warning',
                    default => 'danger',
                }),
                Tables\Columns\TextColumn::make('users_count')->counts('users')->label('Users'),
                Tables\Columns\TextColumn::make('calls_count')->counts('calls')->label('Calls'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => TenantResource\Pages\ListTenants::route('/'),
            'create' => TenantResource\Pages\CreateTenant::route('/create'),
            'edit' => TenantResource\Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
