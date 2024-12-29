<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TicketsOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Tickets overview';
    public ?string $filter = 'week';

    protected function getData(): array
    {
        $start = null;
        $end = null;
        $perData = null;

        switch ($this->filter) {
            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $perData = 'perDay';
                break;

            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $perData = 'perMonth';
                break;

            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $perData = 'perYear';
                break;

        }


        $data = Trend::model(Ticket::class)
            ->between(
                start: $start,
                end: $end
            )
            ->{$perData}()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Tickets Data',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
