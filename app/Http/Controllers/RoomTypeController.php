<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoomTypeController extends Controller
{
    public function index(): View
    {
        $hotelId = auth()->user()->hotel_id;
        $roomTypes = RoomType::where('hotel_id', $hotelId)
            ->withCount('rooms')
            ->orderBy('name')
            ->paginate(20);
        
        return view('room-types.index', compact('roomTypes'));
    }

    public function create(): View
    {
        return view('room-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1|max:10',
            'bed_count' => 'required|integer|min:1|max:5',
            'bed_type' => 'required|string|max:50',
            'room_size' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['hotel_id'] = auth()->user()->hotel_id;
        $validated['is_active'] = $request->has('is_active');

        RoomType::create($validated);

        return redirect()->route('room-types.index')
            ->with('success', 'Type de chambre créé avec succès.');
    }

    public function show(RoomType $roomType): View
    {
        $roomType->load(['rooms', 'reservations.guest']);
        
        return view('room-types.show', compact('roomType'));
    }

    public function edit(RoomType $roomType): View
    {
        return view('room-types.edit', compact('roomType'));
    }

    public function update(Request $request, RoomType $roomType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1|max:10',
            'bed_count' => 'required|integer|min:1|max:5',
            'bed_type' => 'required|string|max:50',
            'room_size' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $roomType->update($validated);

        return redirect()->route('room-types.index')
            ->with('success', 'Type de chambre mis à jour avec succès.');
    }

    public function destroy(RoomType $roomType): RedirectResponse
    {
        if ($roomType->rooms()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer ce type de chambre car il est utilisé par des chambres existantes.');
        }

        $roomType->delete();
        
        return redirect()->route('room-types.index')
            ->with('success', 'Type de chambre supprimé avec succès.');
    }
}
