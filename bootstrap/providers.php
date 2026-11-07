<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AgentPanelProvider;
use App\Providers\Filament\BusinessPanelProvider;
use App\Providers\Filament\PlatformPanelProvider;
use App\Providers\Filament\PortalPanelProvider;

return [
    AppServiceProvider::class,
    PlatformPanelProvider::class,
    BusinessPanelProvider::class,
    AgentPanelProvider::class,
    PortalPanelProvider::class,
];
