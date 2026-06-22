<?php

namespace App\Http\Controllers\Api;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $query = Booking::with(['room.property', 'user']);

        if ($user->role === 'tenant') {
            $query->where(function($q) use ($user) { $q->where('user_id', $user->id); });
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('room', function($qr) use ($search) {
                      $qr->where('room_number', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();
        $rooms = Room::all();

        return response()->json(compact('bookings', 'rooms'));
    }

    public function create(Room $room = null)
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }
        
        if (!Auth::user()->tenantProfile) {
            return redirect()->route('tenant.profile')->withErrors('Please complete your profile before booking.');
        }

        return response()->json(compact('room'));
    }

    public function storeGeneral(Request $request)
    {
        return $this->store($request);
    }

    public function store(Request $request, Room $room = null)
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }

        $request->validate([
            'interview_type' => 'required|in:online,offline',
            'interview_location' => 'nullable|string',
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id ?? null,
            'status' => 'pending',
            'interview_type' => $request->interview_type,
            'interview_date' => null,
            'interview_location' => $request->interview_type === 'offline' ? $request->interview_location : null,
            'interview_link' => null,
        ]);

        return response()->json(['message' => 'Interview booked successfully. Await confirmation.']);
    }

    public function update(Request $request, Booking $booking)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'caretaker'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,scheduled,granted,confirmed,rejected',
            'room_id' => 'nullable|exists:rooms,id',
            'interview_date' => 'nullable|date',
            'interview_link' => 'nullable|string',
            'interview_location' => 'nullable|string',
        ]);

        $data = [
            'status' => $request->status,
        ];

        if ($request->has('interview_date')) {
            $data['interview_date'] = $request->interview_date;
        }
        if ($request->has('interview_link')) {
            $data['interview_link'] = $request->interview_link;
        }
        if ($request->has('interview_location')) {
            $data['interview_location'] = $request->interview_location;
        }
        if ($request->has('room_id')) {
            $data['room_id'] = $request->room_id;
        }

        $booking->update($data);

        $message = 'Booking updated successfully.';
        if ($request->status === 'scheduled') {
            $message = 'Interview scheduled successfully.';
        }
        if ($request->status === 'granted') {
            $message = 'Interview granted! You can now assign a room.';
        }
        if ($request->status === 'confirmed') {
            $message = 'Room assigned and booking confirmed.';
        }

        return response()->json(['message' => $message]);
    }
}
