<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $hotelId = auth()->user()->hotel_id;
        
        $query = Service::where('hotel_id', $hotelId);
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $services = $query->orderBy('category')->orderBy('name')->paginate(20);
        
        return view('services.index', compact('services'));
    }

    public function create(): View
    {
        return view('services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:spa,restaurant,bar,laundry,transport,business_center,fitness,room_service,other',
            'price' => 'required|numeric|min:0',
            'pricing_type' => 'required|in:fixed,per_hour,per_day,per_person',
            'max_capacity' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['hotel_id'] = auth()->user()->hotel_id;
        $validated['is_active'] = $request->has('is_active');

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('success', 'Service créé avec succès.');
    }

    public function show(Service $service): View
    {
        $service->load(['serviceBookings.guest']);
        
        return view('services.show', compact('service'));
    }

    public function edit(Service $service): View
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:spa,restaurant,bar,laundry,transport,business_center,fitness,room_service,other',
            'price' => 'required|numeric|min:0',
            'pricing_type' => 'required|in:fixed,per_hour,per_day,per_person',
            'max_capacity' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $service->update($validated);

        return redirect()->route('services.index')
            ->with('success', 'Service mis à jour avec succès.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();
        
        return redirect()->route('services.index')
            ->with('success', 'Service supprimé avec succès.');
    }
}
