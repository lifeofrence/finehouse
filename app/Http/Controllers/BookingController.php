<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'tenant') {
            $bookings = Booking::with('room.property')->where('user_id', $user->id)->get();
        } else {
            // Basic view for admin/landlord to see bookings. For Phase 4 focus on Tenant flow mostly.
            // But we'll query all bookings for their properties.
            $propertyIds = $user->company->properties->pluck('id')->toArray();
            $roomIds = Room::whereIn('property_id', $propertyIds)->pluck('id')->toArray();
            $bookings = Booking::with('room.property', 'user')->whereIn('room_id', $roomIds)->get();
        }

        return view('booking.index', compact('bookings'));
    }

    public function create(Room $room)
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }
        
        if (!Auth::user()->tenantProfile) {
            return redirect()->route('tenant.profile')->withErrors('Please complete your profile before booking.');
        }

        return view('booking.create', compact('room'));
    }

    public function store(Request $request, Room $room)
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }

        $request->validate([
            'interview_date' => 'required|date|after:today',
            'interview_type' => 'required|in:online,offline',
            'interview_location' => 'nullable|string',
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'status' => 'pending',
            'interview_date' => $request->interview_date,
            'interview_location' => $request->interview_type === 'offline' ? $request->interview_location : null,
            'interview_link' => $request->interview_type === 'online' ? 'To be provided by admin' : null,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Interview booked successfully. Await confirmation.');
    }

    public function update(Request $request, Booking $booking)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'caretaker'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'interview_link' => 'nullable|string',
            'interview_location' => 'nullable|string',
        ]);

        $booking->update([
            'status' => $request->status,
            'interview_link' => $request->interview_link ?? $booking->interview_link,
            'interview_location' => $request->interview_location ?? $booking->interview_location,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }
}
