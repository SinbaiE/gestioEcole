<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $hotelId = auth()->user()->hotel_id;
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(29);

        // Main stats
        $totalRooms = Room::where('hotel_id', $hotelId)->count();
        $occupiedRooms = Reservation::where('hotel_id', $hotelId)
            ->where('check_in_date', '<=', $today)
            ->where('check_out_date', '>', $today)
            ->whereIn('status', ['checked_in'])
            ->count();

        $stats = [
            'total_rooms' => $totalRooms,
            'occupied_rooms' => $occupiedRooms,
            'occupancy_rate' => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0,
        ];

        // Monthly revenue
        $monthlyRevenue = Reservation::where('hotel_id', $hotelId)
            ->whereBetween('check_out_date', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()])
            ->where('status', 'checked_out')
            ->sum('total_amount');

        // Today's arrivals and departures
        $todayArrivals = Reservation::where('hotel_id', $hotelId)
            ->with(['guest', 'roomType', 'room'])
            ->whereDate('check_in_date', $today)
            ->where('status', 'confirmed')
            ->get();

        $todayDepartures = Reservation::where('hotel_id', $hotelId)
            ->with(['guest', 'room'])
            ->whereDate('check_out_date', $today)
            ->where('status', 'checked_in')
            ->get();

        // Recent reservations
        $recentReservations = Reservation::where('hotel_id', $hotelId)
            ->with(['guest', 'roomType'])
            ->latest()
            ->limit(5)
            ->get();

        // Chart Data: Revenue for last 30 days
        $revenueData = Reservation::where('hotel_id', $hotelId)
            ->where('status', 'checked_out')
            ->whereBetween('check_out_date', [$startDate, $today])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE(check_out_date) as date'),
                DB::raw('SUM(total_amount) as total')
            ])
            ->pluck('total', 'date');

        // Chart Data: Occupancy for last 30 days
        $occupancyData = Reservation::where('hotel_id', $hotelId)
            ->whereIn('status', ['checked_in', 'checked_out'])
            ->where('check_in_date', '<', $today)
            ->where('check_out_date', '>=', $startDate)
            ->select('check_in_date', 'check_out_date')
            ->get();

        // Process data for charts
        $chartLabels = [];
        $revenueChartData = [];
        $occupancyChartData = [];

        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateString = $date->format('Y-m-d');
            $chartLabels[] = $date->format('M d');

            // Revenue
            $revenueChartData[] = $revenueData->get($dateString, 0);

            // Occupancy
            $occupiedCount = $occupancyData->filter(function ($res) use ($date) {
                return $date->between(Carbon::parse($res->check_in_date), Carbon::parse($res->check_out_date)->subDay());
            })->count();

            $occupancyChartData[] = $totalRooms > 0 ? round(($occupiedCount / $totalRooms) * 100, 1) : 0;
        }

        $revenueChart = [
            'labels' => $chartLabels,
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenueChartData,
                    'borderColor' => '#4f46e5',
                    'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
        ];

        $occupancyChart = [
            'labels' => $chartLabels,
            'datasets' => [
                [
                    'label' => 'Occupancy Rate (%)',
                    'data' => $occupancyChartData,
                    'backgroundColor' => '#10b981',
                ],
            ],
        ];

        return view('dashboard', compact(
            'stats',
            'monthlyRevenue',
            'todayArrivals',
            'todayDepartures',
            'recentReservations',
            'revenueChart',
            'occupancyChart'
        ));
    }
}
