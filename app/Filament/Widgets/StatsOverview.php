<?php

namespace App\Filament\Widgets;

use App\Models\Proyek;
use App\Models\Keahlian;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Proyek', Proyek::count())
                ->description('Misi terselesaikan')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('success'),
            Stat::make('Total Keahlian', Keahlian::count())
                ->description('Arsenal teknologi')
                ->descriptionIcon('heroicon-m-cpu-chip')
                ->color('info'),
            Stat::make('Core Engine', Keahlian::where('apakah_core', true)->count())
                ->description('Keahlian utama')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('danger'),
        ];
    }
}
