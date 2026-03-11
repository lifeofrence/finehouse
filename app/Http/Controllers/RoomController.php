<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Property;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Admin/Landlords see all rooms in their company's properties.
        // Caretakers see rooms ONLY for their assigned property or company.
        
        $query = Room::with('property', 'images');

        if ($user->role === 'admin' || $user->role === 'landlord') {
            $propertyIds = Property::where('company_id', $user->company_id)->pluck('id');
            $query->whereIn('property_id', $propertyIds->toArray());
        } elseif ($user->role === 'caretaker') {
            if ($user->property_id) {
                $query->where('property_id', $user->property_id);
            } else {
                $propertyIds = Property::where('company_id', $user->company_id)->pluck('id');
                $query->whereIn('property_id', $propertyIds->toArray());
            }
        } else {
            // Tenants browsing rooms (only available ones)
            $query->where('is_available', true);
        }

        $rooms = $query->get();
        return view('room.index', compact('rooms'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'caretaker'])) {
            abort(403, 'Unauthorized');
        }

        if ($user->role === 'caretaker' && $user->property_id) {
            $properties = Property::where('id', $user->property_id)->get();
        } else {
            $properties = Property::where('company_id', $user->company_id)->get();
        }
        
        return view('room.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'room_number' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // max 2MB
        ]);

        $room = Room::create([
            'property_id' => $request->property_id,
            'room_number' => $request->room_number,
            'capacity' => $request->capacity,
            'price' => $request->price,
            'description' => $request->description,
            'is_available' => true,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('rooms', 'public');
                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $path,
                    'display_order' => $index
                ]);
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }

    public function show(string $id)
    {
        $room = Room::with('images', 'property')->findOrFail($id);
        return view('room.show', compact('room'));
    }

    // Keep other methods empty or basic for now
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}

    public function showAssignForm(Room $room)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'caretaker'])) {
            abort(403);
        }

        // Fetch tenants (for now, any user with role 'tenant')
        $tenants = \App\Models\User::where('role', 'tenant')->get();
        return view('room.assign', compact('room', 'tenants'));
    }

    public function assign(Request $request, Room $room)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'caretaker'])) {
            abort(403);
        }

        $request->validate([
            'tenant_id' => 'required|exists:users,id'
        ]);

        $room->update(['is_available' => false]);

        // Typically we would create a Room Assignment or Tenancy record here.
        // For Phase 3, we mark the room occupied and can log the tenant_id in a Tenancy model.
        // Since TenantProfile and Bookings are built in Phase 4, we just block the room now.

        return redirect()->route('rooms.show', $room->id)->with('success', 'Room has been successfully assigned and marked as occupied.');
    }
}
