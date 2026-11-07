<?php

namespace App\Filament\Platform\Widgets;

use App\Models\Call;
use App\Models\Tenant;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $tenantsCount = Tenant::count();
        $usersCount = User::count();
        $callsCount = Call::count();

        return [
            Stat::make('Active Tenants', number_format($tenantsCount))
                ->description('Apex Sales Co, Nova Realty, Solo Closer')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),

            Stat::make('Registered Seats', number_format($usersCount))
                ->description('Across all branches')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Global Calls Ingested', number_format($callsCount))
                ->description('AI speech-to-text & scoring')
                ->descriptionIcon('heroicon-m-cpu-chip')
                ->color('primary'),
        ];
    }
}
