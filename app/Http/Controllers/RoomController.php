<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('roomType');

        // Filtres
        if ($request->filled('search')) {
            $query->where('room_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('room_type')) {
            $query->where('room_type_id', $request->room_type);
        }

        $rooms = $query->orderBy('room_number')->paginate(15);
        $roomTypes = RoomType::orderBy('name')->get();

        return view('rooms.index', compact('rooms', 'roomTypes'));
    }

    public function create()
    {
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        return view('rooms.create', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required|string|max:20|unique:rooms,room_number',
            'floor' => 'required|string|max:10',
            'status' => 'required|in:available,occupied,maintenance,cleaning,out_of_order',
            'housekeeping_status' => 'required|in:clean,dirty,inspected,maintenance',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $room = Room::create([
            'room_type_id' => $request->room_type_id,
            'room_number' => $request->room_number,
            'floor' => $request->floor,
            'status' => $request->status,
            'housekeeping_status' => $request->housekeeping_status,
            'notes' => $request->notes,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imagefile) {
                $path = $imagefile->store('room_images', 'public');
                $room->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('rooms.index')
            ->with('success', 'Chambre créée avec succès.');
    }

    public function show(Room $room)
    {
        $room->load(['roomType', 'reservations.guest']);
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        return view('rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required|string|max:20|unique:rooms,room_number,' . $room->id,
            'floor' => 'required|string|max:10',
            'status' => 'required|in:available,occupied,maintenance,cleaning,out_of_order',
            'housekeeping_status' => 'required|in:clean,dirty,inspected,maintenance',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:images,id',
        ]);

        $room->update($request->except(['images', 'delete_images']));

        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = Image::find($imageId);
                if ($image && $image->imageable_id === $room->id && $image->imageable_type === get_class($room)) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imagefile) {
                $path = $imagefile->store('room_images', 'public');
                $room->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Chambre mise à jour avec succès.');
    }

    public function updateStatus(Request $request, Room $room)
    {
        $request->validate([
            'status' => 'required|in:available,occupied,maintenance,cleaning,out_of_order',
            'housekeeping_status' => 'required|in:clean,dirty,inspected,maintenance',
        ]);

        $room->update([
            'status' => $request->status,
            'housekeeping_status' => $request->housekeeping_status,
        ]);

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Statut de la chambre mis à jour avec succès.');
    }

    public function destroy(Room $room)
    {
        // Vérifier s'il y a des réservations actives
        if ($room->reservations()->whereIn('status', ['confirmed', 'checked_in'])->exists()) {
            return redirect()->back()
                ->with('error', 'Cette chambre ne peut pas être supprimée car elle a des réservations actives.');
        }

        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Chambre supprimée avec succès.');
    }
}
