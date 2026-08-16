<?php

namespace App\Filament\Resources\ProjectResource\Widgets;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $base = Project::query();

        return [
            Stat::make('Proyek Berjalan', $base->clone()->where('status', ProjectStatus::Ongoing->value)->count())
                ->description('Status ongoing')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),
            Stat::make('Proyek Selesai', $base->clone()->where('status', ProjectStatus::Completed->value)->count())
                ->description('Status selesai')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Dengan Klien BUMN', $base->clone()->where('client_type', 'bumn')->count())
                ->description('Portofolio BUMN')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('warning'),
            Stat::make('Total Proyek', $base->clone()->count())
                ->description('Semua status')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary'),
        ];
    }
}
