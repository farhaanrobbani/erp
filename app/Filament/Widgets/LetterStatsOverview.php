<?php

namespace App\Filament\Widgets;

use App\Models\LetterRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LetterStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        $base = LetterRequest::query();

        if ($user && ! $user->can('approve_letter-request') && ! $user->hasRole('super_admin')) {
            $base->where('user_id', $user->id);
        }

        return [
            Stat::make('Pengajuan Pending', $base->clone()->where('status', 'pending')->count())
                ->description('Menunggu persetujuan')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Disetujui', $base->clone()->where('status', 'approved')->count())
                ->description('Nomor surat diterbitkan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Ditolak', $base->clone()->where('status', 'rejected')->count())
                ->description('Pengajuan ditolak')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
            Stat::make('Total Pengajuan', $base->clone()->count())
                ->description('Semua status')
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color('primary'),
        ];
    }
}
