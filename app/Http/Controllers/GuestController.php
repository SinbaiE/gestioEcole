<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class GuestController extends Controller
{
    public function index(Request $request): View
    {
        $query = Guest::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }
        
        if ($request->filled('guest_type')) {
            $query->where('guest_type', $request->guest_type);
        }

        $guests = $query->withCount('reservations')->orderBy('created_at', 'desc')->paginate(20);
        
        return view('guests.index', compact('guests'));
    }

    public function create(): View
    {
        return view('guests.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:guests',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'nationality' => 'nullable|string|max:100',
            'id_type' => 'nullable|string|max:50',
            'id_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'guest_type' => 'required|in:individual,corporate,group,vip',
        ]);

        Guest::create($validated);

        return redirect()->route('guests.index')
            ->with('success', 'Client créé avec succès.');
    }

    public function show(Guest $guest): View
    {
        $guest->load(['reservations.roomType', 'reservations.room']);
        
        return view('guests.show', compact('guest'));
    }

    public function edit(Guest $guest): View
    {
        return view('guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:guests,email,' . $guest->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'nationality' => 'nullable|string|max:100',
            'id_type' => 'nullable|string|max:50',
            'id_number' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'guest_type' => 'required|in:individual,corporate,group,vip',
        ]);

        $guest->update($validated);

        return redirect()->route('guests.index')
            ->with('success', 'Client mis à jour avec succès.');
    }
}
