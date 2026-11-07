<?php

namespace App\Filament\Business\Resources;

use App\Facades\Module;
use App\Models\TenantModule;
use App\Services\Tenancy\TenantContext;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TenantModuleResource extends Resource
{
    protected static ?string $model = TenantModule::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $navigationGroup = 'Settings & Modules';

    protected static ?string $navigationLabel = 'Module Manager';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('module_key')->disabled(),
                Forms\Components\Toggle::make('enabled')
                    ->label('Module Enabled')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('module.name')
                    ->label('Module Name')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (TenantModule $record): string => $record->module instanceof \App\Models\Module ? $record->module->description : ''),

                Tables\Columns\TextColumn::make('module.category')
                    ->label('Category')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('module.is_core')
                    ->label('Core Module')
                    ->boolean(),

                Tables\Columns\ToggleColumn::make('enabled')
                    ->label('Status')
                    ->beforeStateUpdated(function (TenantModule $record, $state) {
                        $tenant = TenantContext::getTenant();
                        if (! $tenant) {
                            return;
                        }

                        if ($state) {
                            Module::enable($record->module_key, $tenant);
                        } else {
                            Module::disable($record->module_key, $tenant);
                        }
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('apply_preset')
                    ->label('Apply Business Preset')
                    ->icon('heroicon-o-sparkles')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('preset')
                            ->label('Select Preset')
                            ->options([
                                'call_center' => 'Call Center & BPO (All 22 Modules ON)',
                                'saas_sales' => 'SaaS Sales Team (Pipeline, Demos, Coaching)',
                                'real_estate' => 'Real Estate Sales Team (Dialer, Follow-ups)',
                                'insurance' => 'Insurance & Banking (Compliance & QA focus)',
                                'solo_freelancer' => 'Solo Freelancer (Minimal Modules - Proves Modules OFF)',
                            ])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $tenant = TenantContext::getTenant();
                        if ($tenant) {
                            Module::applyPreset($data['preset'], $tenant);
                            Notification::make()
                                ->title('Preset Applied')
                                ->body("Module preset '{$data['preset']}' successfully activated.")
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->actions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => TenantModuleResource\Pages\ListTenantModules::route('/'),
        ];
    }
}
