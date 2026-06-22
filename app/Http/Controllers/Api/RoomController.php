<?php

namespace App\Http\Controllers\Api;

use App\Models\Room;
use App\Models\Property;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $propertyId = $request->get('property_id');
        $search = $request->get('search');
        
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = Room::query()->with([
            'property', 
            'images', 
            'bookings' => function($q) {
                $q->whereIn('status', ['confirmed', 'approved'])->latest();
            },
            'bookings.user.tenantProfile'
        ]);

        // Search logic
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // RBAC / Scoping
        if ($user->role === 'super_admin') {
            if ($propertyId) {
                $query->where('property_id', '=', $propertyId);
            }
            $properties = Property::all();
        } elseif (in_array($user->role, ['admin', 'landlord', 'caretaker'])) {
            $companyProperties = Property::where('company_id', $user->company_id);
            $propertyIds = $companyProperties->pluck('id');
            $properties = $companyProperties->get();
            
            if ($propertyId) {
                if ($propertyIds->contains($propertyId)) {
                    $query->where('property_id', $propertyId);
                } else {
                    abort(403);
                }
            } else {
                if ($user->role === 'caretaker' && $user->property_id) {
                    $query->where('property_id', $user->property_id);
                } else {
                    $query->whereIn('property_id', $propertyIds->toArray());
                }
            }
        } else {
            $query->where('is_available', '=', true);
            $properties = collect(); // Tenants don't get the filter list usually, but could be added if needed
        }

        $rooms = $query->get();
        $selectedProperty = $propertyId ? Property::find($propertyId) : null;
        
        $movingTenant = null;
        if ($request->has('move_tenant_id')) {
            $movingTenant = \App\Models\User::find($request->move_tenant_id);
        }
        
        return response()->json(compact('rooms', 'selectedProperty', 'properties', 'propertyId', 'search', 'movingTenant'));
    }

    public function assignment(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'admin', 'landlord', 'caretaker'])) {
            abort(403);
        }

        $propertyId = $request->get('property_id');
        
        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = Room::query()->with([
            'property', 
            'bookings' => function($q) {
                $q->whereIn('status', ['confirmed', 'approved'])->with('user');
            }
        ]);

        if ($user->role !== 'super_admin') {
            $companyProperties = Property::where('company_id', $user->company_id);
            $propertyIds = $companyProperties->pluck('id');
            $query->whereIn('property_id', $propertyIds);
        }

        if ($propertyId) {
            $query->where('property_id', '=', $propertyId);
        }

        $rooms = $query->get();
        $properties = $user->role === 'super_admin' ? Property::all() : Property::where('company_id', $user->company_id)->get();

        return response()->json(compact('rooms', 'properties', 'propertyId'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'admin', 'landlord', 'caretaker'])) {
            abort(403, 'Unauthorized');
        }

        $preSelectedPropertyId = $request->get('property_id');

        if ($user->role === 'super_admin') {
            $properties = Property::all();
        } elseif ($user->role === 'caretaker' && $user->property_id) {
            $properties = Property::where('id', $user->property_id)->get();
        } else {
            $properties = Property::where('company_id', $user->company_id)->get();
        }
        
        return response()->json(compact('properties', 'preSelectedPropertyId'));
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

        return redirect()->route('rooms.index', ['property_id' => $request->property_id])
            ->with('success', 'Room created successfully.');
    }

    public function show(string $id)
    {
        $room = Room::with(['images', 'property', 'bookings' => function($q) {
            $q->whereIn('status', ['confirmed', 'approved'])->with('user');
        }])->findOrFail($id);
        
        return response()->json(compact('room'));
    }

    public function edit(string $id)
    {
        $user = Auth::user();
        $room = Room::with('images')->findOrFail($id);
        
        // Authorization
        if ($user->role !== 'super_admin') {
            $property = Property::find($room->property_id);
            if (!$property || $property->company_id !== $user->company_id) {
                abort(403);
            }
        }

        if ($user->role === 'super_admin') {
            $properties = Property::all();
        } else {
            $properties = Property::where('company_id', $user->company_id)->get();
        }

        return response()->json(compact('room', 'properties'));
    }

    public function update(Request $request, string $id)
    {
        $room = Room::findOrFail($id);
        
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'room_number' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // max 2MB
        ]);

        $room->update($request->only(['property_id', 'room_number', 'capacity', 'price', 'description']));

        if ($request->hasFile('images')) {
            $lastOrder = $room->images()->max('display_order') ?? -1;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('rooms', 'public');
                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $path,
                    'display_order' => $lastOrder + $index + 1
                ]);
            }
        }

        return redirect()->route('rooms.index', ['property_id' => $room->property_id])
            ->with('success', 'Room updated successfully.');
    }

    public function deleteImage(RoomImage $roomImage)
    {
        $user = Auth::user();
        $room = $roomImage->room;
        $property = $room->property;
        
        // Authorization
        if ($user->role !== 'super_admin' && $property->company_id !== $user->company_id) {
            abort(403);
        }

        // Delete from storage
        if (Storage::disk('public')->exists($roomImage->image_path)) {
            Storage::disk('public')->delete($roomImage->image_path);
        }
        
        $roomImage->delete();

        return back()->with('success', 'Image removed successfully.');
    }

    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);
        $propertyId = $room->property_id;
        
        // Delete all images first
        foreach($room->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        $room->delete();

        return redirect()->route('rooms.index', ['property_id' => $propertyId])
            ->with('success', 'Room deleted successfully.');
    }

    public function showAssignForm(Room $room, Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'admin', 'landlord', 'caretaker'])) {
            abort(403);
        }

        // Calculate current occupancy
        $occupancyCount = $room->bookings()->whereIn('status', ['confirmed', 'approved'])->count();
        
        if ($occupancyCount >= $room->capacity) {
            return response()->json(['message' => 'This room is already at its maximum capacity (' . $room->capacity . ').']);
        }

        $preselectedTenantId = $request->get('tenant_id');

        // Fetch tenants belonging to the same company as the property who are NOT already assigned to any room
        $companyId = $room->property->company_id;
        $tenants = \App\Models\User::where('role', 'tenant')
            ->where('company_id', $companyId)
            ->whereDoesntHave('bookings', function($q) {
                $q->whereIn('status', ['confirmed', 'approved']);
            })
            ->get();

        return response()->json(compact('room', 'tenants', 'occupancyCount', 'preselectedTenantId'));
    }

    public function assign(Request $request, Room $room)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'admin', 'landlord', 'caretaker'])) {
            abort(403);
        }

        $request->validate([
            'tenant_id' => 'required|exists:users,id'
        ]);

        // Security check: Verify tenant belongs to the same company as the room
        $tenant = \App\Models\User::findOrFail($request->tenant_id);
        if ($tenant->company_id !== $room->property->company_id) {
            return back()->with('error', 'Unauthorized: Tenant belongs to a different organization.');
        }

        // Integrity check: Verify tenant isn't already assigned to another room
        $existingBooking = \App\Models\Booking::where('user_id', $request->tenant_id)
            ->whereIn('status', ['confirmed', 'approved'])
            ->first();
        
        if ($existingBooking) {
            return back()->with('error', 'Integrity Error: This tenant is already assigned to ' . ($existingBooking->room->room_number ?? 'another room') . '.');
        }

        // Verify capacity once more before creating
        $occupancyCount = $room->bookings()->whereIn('status', ['confirmed', 'approved'])->count();
        
        if ($occupancyCount >= $room->capacity) {
             return back()->withErrors(['tenant_id' => 'Room is already at full capacity.']);
        }

        // Create a confirmed booking for this assignment
        \App\Models\Booking::create([
            'user_id' => $request->tenant_id,
            'room_id' => $room->id,
            'status' => 'confirmed',
            'interview_date' => now(), // Manual assignment bypasses interview, but we fill for schema
        ]);

        // Mark as unavailable ONLY if capacity is reached
        if ($occupancyCount + 1 >= $room->capacity) {
            $room->update(['is_available' => true]); // Keep it available if there is still space, but wait...
            // Actually the logic should be: if count >= capacity, then is_available = false.
            if ($occupancyCount + 1 >= $room->capacity) {
                $room->update(['is_available' => false]);
            }
        }

        return response()->json(['message' => 'Tenant has been assigned. Room occupancy: ' . ($occupancyCount + 1) . '/' . $room->capacity]);
    }

    public function unassign(Request $request, Room $room, \App\Models\Booking $booking)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'admin', 'landlord', 'caretaker'])) {
            abort(403);
        }

        // Ensure the booking belongs to this room
        if ($booking->room_id !== $room->id) {
            abort(404);
        }

        // We can either delete the booking or change status to 'completed' or 'cancelled'
        // For "removing", we'll mark it as 'completed' to keep history, or just delete if preferred.
        // Let's use 'cancelled' or delete. The user says "remove".
        $booking->delete();

        // After removing, room is definitely available if it wasn't before
        $room->update(['is_available' => true]);

        return response()->json(['message' => 'Tenant has been removed from this room.']);
    }
}
