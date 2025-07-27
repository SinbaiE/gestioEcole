<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\ServiceBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('reports.index');
    }

    public function occupancy(Request $request): View
    {
        $hotelId = auth()->user()->hotel_id;
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        
        $totalRooms = Room::where('hotel_id', $hotelId)->count();
        
        // Données d'occupation par jour
        $occupancyData = [];
        $revenueData = [];
        $current = Carbon::parse($startDate);
        
        while ($current <= Carbon::parse($endDate)) {
            $occupiedRooms = Reservation::where('hotel_id', $hotelId)
                ->where('check_in_date', '<=', $current)
                ->where('check_out_date', '>', $current)
                ->where('status', 'checked_in')
                ->count();
                
            $dailyRevenue = Reservation::where('hotel_id', $hotelId)
                ->whereDate('check_in_date', $current)
                ->where('status', 'checked_out')
                ->sum('total_amount');
            
            $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
            
            $occupancyData[] = [
                'date' => $current->format('Y-m-d'),
                'occupied_rooms' => $occupiedRooms,
                'total_rooms' => $totalRooms,
                'occupancy_rate' => round($occupancyRate, 1),
            ];
            
            $revenueData[] = [
                'date' => $current->format('Y-m-d'),
                'revenue' => $dailyRevenue,
            ];
            
            $current->addDay();
        }
        
        return view('reports.occupancy', compact('occupancyData', 'revenueData', 'startDate', 'endDate'));
    }

    public function revenue(Request $request): View
    {
        $hotelId = auth()->user()->hotel_id;
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        
        // Revenus par mois
        $monthlyRevenue = Reservation::where('hotel_id', $hotelId)
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->where('status', 'checked_out')
            ->selectRaw('YEAR(check_in_date) as year, MONTH(check_in_date) as month, SUM(total_amount) as revenue')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Revenus par type de chambre
        $revenueByRoomType = Reservation::where('hotel_id', $hotelId)
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->where('status', 'checked_out')
            ->join('room_types', 'reservations.room_type_id', '=', 'room_types.id')
            ->selectRaw('room_types.name, SUM(reservations.total_amount) as revenue, COUNT(*) as bookings')
            ->groupBy('room_types.id', 'room_types.name')
            ->get();
        
        // Revenus des services
        $serviceRevenue = ServiceBooking::where('hotel_id', $hotelId)
            ->whereBetween('service_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->join('services', 'service_bookings.service_id', '=', 'services.id')
            ->selectRaw('services.category, SUM(service_bookings.total_amount) as revenue')
            ->groupBy('services.category')
            ->get();
        
        return view('reports.revenue', compact('monthlyRevenue', 'revenueByRoomType', 'serviceRevenue', 'startDate', 'endDate'));
    }

    public function guests(Request $request): View
    {
        $hotelId = auth()->user()->hotel_id;
        
        // Statistiques des clients
        $guestStats = [
            'total_guests' => Guest::count(),
            'new_guests_this_month' => Guest::whereMonth('created_at', now()->month)->count(),
            'vip_guests' => Guest::where('guest_type', 'vip')->count(),
            'corporate_guests' => Guest::where('guest_type', 'corporate')->count(),
        ];
        
        // Top clients par revenus
        $topGuests = Guest::withSum(['reservations' => function($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId)->where('status', 'checked_out');
            }], 'total_amount')
            ->orderBy('reservations_sum_total_amount', 'desc')
            ->limit(10)
            ->get();
        
        // Répartition par nationalité
        $guestsByNationality = Guest::selectRaw('nationality, COUNT(*) as count')
            ->whereNotNull('nationality')
            ->groupBy('nationality')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        return view('reports.guests', compact('guestStats', 'topGuests', 'guestsByNationality'));
    }
}
