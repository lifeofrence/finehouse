<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'tenant') {
            $requests = MaintenanceRequest::with('property', 'room')->where('user_id', $user->id)->get();
        } else {
            // Admin, landlord, caretaker
            $propertyIds = Property::where('company_id', $user->company_id)->pluck('id');
            $requests = MaintenanceRequest::with('user', 'property', 'room')
                ->whereIn('property_id', $propertyIds->toArray())
                ->get();
        }

        return view('maintenance.index', compact('requests'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }

        // Must have an approved booking / assigned room to report
        $booking = Auth::user()->bookings()->where('status', 'approved')->first();
        if (!$booking) {
            return redirect()->route('dashboard')->withErrors('You need an assigned room to report an incident.');
        }

        return view('maintenance.create', ['room' => $booking->room]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'issue_description' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $booking = Auth::user()->bookings()->where('room_id', $request->room_id)->where('status', 'approved')->firstOrFail();
        
        $maintenance = new MaintenanceRequest([
            'user_id' => Auth::id(),
            'property_id' => $booking->room->property_id,
            'room_id' => $booking->room_id,
            'issue_description' => $request->issue_description,
            'status' => 'pending'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('maintenance', 'public');
            $maintenance->image_path = $path;
        }

        $maintenance->save();

        return redirect()->route('maintenance.index')->with('success', 'Incident reported successfully.');
    }

    public function update(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'caretaker'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved'
        ]);

        $maintenanceRequest->update(['status' => $request->status]);

        return back()->with('success', 'Maintenance status updated.');
    }
}
