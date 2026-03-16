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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = Announcement::with('user', 'property', 'targetUser')->latest();

        if ($user->role === 'tenant') {
            // Tenants see announcements for:
            // 1. Their property (where they have a confirmed/approved booking)
            // 2. Announcements targeted specifically to them
            $query->where(function($q) use ($user) {
                $booking = $user->bookings()->whereIn('status', ['confirmed', 'approved'])->first();
                if ($booking) {
                    $q->where('property_id', $booking->room->property_id);
                }
                $q->orWhere('target_user_id', $user->id);
            });
        } elseif ($user->role === 'lodge_president' || $user->role === 'caretaker') {
            if ($user->property_id) {
                $query->where(function($q) use ($user) {
                    $q->where('property_id', $user->property_id)
                      ->orWhere('user_id', $user->id); // See their own
                });
            } else {
                $propertyIds = Property::where('company_id', $user->company_id)->pluck('id');
                $query->where(function($q) use ($propertyIds, $user) {
                    $q->whereIn('property_id', $propertyIds->toArray())
                      ->orWhere('user_id', $user->id);
                });
            }
        } else {
            // Landlord/Admin
            $propertyIds = Property::where('company_id', $user->company_id)->pluck('id');
            $query->where(function($q) use ($propertyIds, $user) {
                $q->whereIn('property_id', $propertyIds->toArray())
                  ->orWhere('user_id', $user->id);
            });
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
            $users = \App\Models\User::where('property_id', $user->property_id)
                     ->where('role', 'tenant')->get();
        } else {
            $properties = Property::where('company_id', $user->company_id)->get();
            $users = \App\Models\User::where('company_id', $user->company_id)
                     ->where('role', 'tenant')->get();
        }

        return view('announcement.create', compact('properties', 'users'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'lodge_president', 'caretaker'])) {
            abort(403);
        }

        $request->validate([
            'target_type' => 'required|in:property,individual',
            'property_id' => 'required_if:target_type,property|nullable|exists:properties,id',
            'target_user_id' => 'required_if:target_type,individual|nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Announcement::create([
            'user_id' => $user->id,
            'property_id' => $request->target_type === 'property' ? $request->property_id : null,
            'target_user_id' => $request->target_type === 'individual' ? $request->target_user_id : null,
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return redirect()->route('announcements.index')->with('success', 'Announcement published.');
    }
}
