<?php

namespace App;

use App\Models\Operator;
use App\Models\Trip;
use App\Models\Region;
use App\Models\Vehicle;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\VehicleInventory;

class Dashboard
{
    public static function charts()
    {
        $regions = Region::withCount('vehicles')->get();

        $period = Carbon::now()->subMonths(12)->monthsUntil(now());

        $lastTwelveMonths = collect([]);

        foreach ($period as $date) {
            $lastTwelveMonths[] = $date->format('Y-m');
        }

        $vehiclesGroupedByMonthAndYear = Vehicle::selectRaw('DATE_FORMAT(created_at, "%Y-%m") AS new_date, COUNT(id) AS vehicles_count')
            ->groupBy('new_date')
            ->get()
            ->pluck('vehicles_count', 'new_date');

//        dd($vehiclesGroupedByMonthAndYear);

        $merged = $lastTwelveMonths->map(function ($month) use ($vehiclesGroupedByMonthAndYear) {
            return [
                'month' => $month,
                'count' => $vehiclesGroupedByMonthAndYear[$month] ?? 0,
            ];
        })->pluck('count', 'month');

//        dd($vehiclesGroupedByMonthAndYear);

        return view('dashboard.charts', compact(
            'merged',
            'regions')
        );
    }

    public static function onboardedOperators()
    {
        return view('card')
            ->with([
                'cardIcon'  => 'gears',
                'cardColor' => 'blue',
                'cardTitle' => 'No. of Onboarded Operators',
                'cardValue' => number_format(Operator::count()),
                'cardAction'=> url('/auth/operators')
            ]);
    }

    public static function onboardedVehicles()
    {
        return view('card')
            ->with([
                'cardIcon'  => 'bus',
                'cardColor' => 'green',
                'cardTitle' => 'No. of Onboarded Vehicles',
                'cardValue' => number_format(VehicleInventory::count()),
                'cardAction'=> url('/auth/inventory')
            ]);
    }

    public static function trips()
    {
        return view('card')
            ->with([
                'cardIcon'  => 'road',
                'cardColor' => 'red',
                'cardTitle' => 'Total No. of Trips',
                'cardValue' => number_format(Trip::count()),
                'cardAction'=> url('/auth/trips')
            ]);
    }
}
