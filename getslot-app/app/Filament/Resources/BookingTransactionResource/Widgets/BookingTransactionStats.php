<?php

namespace App\Filament\Resources\BookingTransactionResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\BookingTransaction;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingTransactionStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalTransaction = BookingTransaction::count();
        $approvedTransaction = BookingTransaction::where('is_paid', true)->count();
        $totalRevenue = BookingTransaction::where('is_paid', true)->sum('total_amount');
        // dd($totalRevenue);

        return [
            //
                Stat::make('Total Transaction', $totalTransaction)
                ->description('All Transaction')
                ->descriptionIcon('heroicon-o-currency-dollar'),

                Stat::make('Approved Transaction', $approvedTransaction)
                ->description('Approved transaction')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

                Stat::make('Total Revenue', 'IDR ' . number_format($totalRevenue))
                ->description('Revenue from approved Transaction')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
