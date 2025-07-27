<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Guest;
use App\Models\RoomType;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['guest', 'roomType', 'room']);

        // Filtres
        if ($request->filled('search')) {
            $query->whereHas('guest', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('check_in_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('check_out_date', '<=', $request->date_to);
        }

        $reservations = $query->latest()->paginate(15);

        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $guests = Guest::orderBy('first_name')->get();
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();

        return view('reservations.create', compact('guests', 'roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_type_id' => 'required|exists:room_types,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:10',
            'room_rate' => 'required|numeric|min:0',
            'special_requests' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $nights = $checkIn->diffInDays($checkOut);
        $totalAmount = $nights * $request->room_rate;

        $reservation = Reservation::create([
            'reservation_number' => 'RES-' . strtoupper(uniqid()),
            'guest_id' => $request->guest_id,
            'room_type_id' => $request->room_type_id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,
            'nights' => $nights,
            'room_rate' => $request->room_rate,
            'total_amount' => $totalAmount,
            'special_requests' => $request->special_requests,
            'notes' => $request->notes,
            'status' => 'pending',
            'source' => 'direct',
            'payment_status' => 'pending',
        ]);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Réservation créée avec succès.');
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['guest', 'roomType', 'room', 'invoices']);
        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        if (in_array($reservation->status, ['checked_out', 'cancelled'])) {
            return redirect()->route('reservations.show', $reservation)
                ->with('error', 'Cette réservation ne peut plus être modifiée.');
        }

        $guests = Guest::orderBy('first_name')->get();
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();

        return view('reservations.edit', compact('reservation', 'guests', 'roomTypes'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        if (in_array($reservation->status, ['checked_out', 'cancelled'])) {
            return redirect()->route('reservations.show', $reservation)
                ->with('error', 'Cette réservation ne peut plus être modifiée.');
        }

        $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_type_id' => 'required|exists:room_types,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:10',
            'room_rate' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled,no_show',
            'special_requests' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $nights = $checkIn->diffInDays($checkOut);
        $totalAmount = $nights * $request->room_rate;

        $reservation->update([
            'guest_id' => $request->guest_id,
            'room_type_id' => $request->room_type_id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,
            'nights' => $nights,
            'room_rate' => $request->room_rate,
            'total_amount' => $totalAmount,
            'status' => $request->status,
            'special_requests' => $request->special_requests,
            'notes' => $request->notes,
        ]);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Réservation mise à jour avec succès.');
    }

    public function checkIn(Reservation $reservation)
    {
        if ($reservation->status !== 'confirmed') {
            return redirect()->back()
                ->with('error', 'Seules les réservations confirmées peuvent être enregistrées.');
        }

        // Assigner une chambre disponible si pas encore fait
        if (!$reservation->room_id) {
            $availableRoom = Room::where('room_type_id', $reservation->room_type_id)
                ->where('status', 'available')
                ->where('is_active', true)
                ->first();

            if (!$availableRoom) {
                return redirect()->back()
                    ->with('error', 'Aucune chambre disponible pour ce type.');
            }

            $reservation->room_id = $availableRoom->id;
            $availableRoom->update(['status' => 'occupied']);
        }

        $reservation->update([
            'status' => 'checked_in',
            'checked_in_at' => now(),
        ]);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Check-in effectué avec succès.');
    }

    public function checkOut(Reservation $reservation)
    {
        if ($reservation->status !== 'checked_in') {
            return redirect()->back()
                ->with('error', 'Seules les réservations enregistrées peuvent être libérées.');
        }

        DB::transaction(function () use ($reservation) {
            $reservation->update([
                'status' => 'checked_out',
                'checked_out_at' => now(),
            ]);

            // Libérer la chambre
            if ($reservation->room) {
                $reservation->room->update([
                    'status' => 'cleaning',
                    'housekeeping_status' => 'dirty'
                ]);
            }

            // Mettre à jour les points de fidélité du client
            $loyaltyPoints = floor($reservation->total_amount / 1000);
            $reservation->guest->increment('loyalty_points', $loyaltyPoints);
            $reservation->guest->increment('total_stays');
            $reservation->guest->update(['last_stay' => now()]);
        });

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Check-out effectué avec succès.');
    }

    public function cancel(Reservation $reservation)
    {
        if (in_array($reservation->status, ['checked_out', 'cancelled'])) {
            return redirect()->back()
                ->with('error', 'Cette réservation ne peut pas être annulée.');
        }

        DB::transaction(function () use ($reservation) {
            $reservation->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // Libérer la chambre si elle était assignée
            if ($reservation->room && $reservation->room->status === 'occupied') {
                $reservation->room->update(['status' => 'available']);
            }
        });

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Réservation annulée avec succès.');
    }

    public function destroy(Reservation $reservation)
    {
        if (in_array($reservation->status, ['checked_in', 'checked_out'])) {
            return redirect()->back()
                ->with('error', 'Cette réservation ne peut pas être supprimée.');
        }

        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation supprimée avec succès.');
    }
}
