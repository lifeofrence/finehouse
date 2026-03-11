<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Announcement::with('user', 'property')->latest();

        if ($user->role === 'tenant') {
            // Tenants only see announcements for properties they have an approved booking for
            $booking = $user->bookings()->where('status', 'approved')->first();
            if ($booking) {
                $query->where('property_id', $booking->room->property_id);
            } else {
                // Return empty if no approved room
                $query->where('id', -1);
            }
        } elseif ($user->role === 'lodge_president' || $user->role === 'caretaker') {
            if ($user->property_id) {
                $query->where('property_id', $user->property_id);
            } else {
                $propertyIds = Property::where('company_id', $user->company_id)->pluck('id');
                $query->whereIn('property_id', $propertyIds->toArray());
            }
        } else {
            // Landlord/Admin
            $propertyIds = Property::where('company_id', $user->company_id)->pluck('id');
            $query->whereIn('property_id', $propertyIds->toArray());
        }

        $announcements = $query->get();
        return view('announcement.index', compact('announcements'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'lodge_president', 'caretaker'])) {
            abort(403);
        }

        if ($user->property_id) {
            $properties = Property::where('id', $user->property_id)->get();
        } else {
            $properties = Property::where('company_id', $user->company_id)->get();
        }

        return view('announcement.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'lodge_president', 'caretaker'])) {
            abort(403);
        }

        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Announcement::create([
            'user_id' => $user->id,
            'property_id' => $request->property_id,
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return redirect()->route('announcements.index')->with('success', 'Announcement published.');
    }
}
