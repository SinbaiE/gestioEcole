<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $hotelId = auth()->user()->hotel_id;
        $today = Carbon::today();

        // Statistiques principales
        $stats = [
            'total_rooms' => Room::where('hotel_id', $hotelId)->count(),
            'occupied_rooms' => Room::where('hotel_id', $hotelId)->where('status', 'occupied')->count(),
            'available_rooms' => Room::where('hotel_id', $hotelId)->where('status', 'available')->count(),
            'maintenance_rooms' => Room::where('hotel_id', $hotelId)->where('status', 'maintenance')->count(),
        ];

        $stats['occupancy_rate'] = $stats['total_rooms'] > 0 
            ? round(($stats['occupied_rooms'] / $stats['total_rooms']) * 100, 1) 
            : 0;

        // Réservations du jour
        $todayReservations = [
            'arrivals' => Reservation::where('hotel_id', $hotelId)
                ->where('check_in_date', $today)
                ->where('status', 'confirmed')
                ->count(),
            'departures' => Reservation::where('hotel_id', $hotelId)
                ->where('check_out_date', $today)
                ->where('status', 'checked_in')
                ->count(),
            'in_house' => Reservation::where('hotel_id', $hotelId)
                ->where('status', 'checked_in')
                ->count(),
        ];

        // Revenus du mois
        $monthlyRevenue = Reservation::where('hotel_id', $hotelId)
            ->whereMonth('check_in_date', $today->month)
            ->whereYear('check_in_date', $today->year)
            ->where('status', 'checked_out')
            ->sum('total_amount');

        // Réservations récentes
        $recentReservations = Reservation::where('hotel_id', $hotelId)
            ->with(['guest', 'roomType'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Arrivées du jour
        $todayArrivals = Reservation::where('hotel_id', $hotelId)
            ->with(['guest', 'roomType', 'room'])
            ->where('check_in_date', $today)
            ->where('status', 'confirmed')
            ->get();

        // Départs du jour
        $todayDepartures = Reservation::where('hotel_id', $hotelId)
            ->with(['guest', 'room'])
            ->where('check_out_date', $today)
            ->where('status', 'checked_in')
            ->get();

        return view('dashboard', compact(
            'stats',
            'todayReservations',
            'monthlyRevenue',
            'recentReservations',
            'todayArrivals',
            'todayDepartures'
        ));
    }
}
