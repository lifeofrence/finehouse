<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $status = $request->get('status');
        $sort = $request->get('sort', 'latest');

        $query = MaintenanceRequest::query();

        if ($user->role === 'tenant') {
            $query->where('user_id', '=', $user->id)->with(['property', 'room']);
        } else {
            $propertyIds = Property::where('company_id', $user->company_id)->pluck('id');
            $query->whereIn('property_id', $propertyIds->toArray())->with(['user', 'property', 'room']);
        }

        if ($search) {
            $query->where('issue_description', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', '=', $status);
        }

        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'pending':
                $query->orderByRaw("FIELD(status, 'pending', 'in_progress', 'resolved')");
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $requests = $query->paginate(10)->withQueryString();

        return view('maintenance.index', compact('requests', 'search', 'status', 'sort'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }

        // Must have an approved/confirmed booking or assigned room to report
        $booking = Auth::user()->bookings()
            ->whereIn('status', ['confirmed', 'approved'])
            ->first();

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

        $booking = Auth::user()->bookings()
            ->where('room_id', $request->room_id)
            ->whereIn('status', ['confirmed', 'approved'])
            ->firstOrFail();
        
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
            'status' => 'required|in:pending,in_progress,resolved',
            'cost' => 'nullable|numeric|min:0'
        ]);

        $maintenanceRequest->update([
            'status' => $request->status,
            'cost' => $request->cost
        ]);

        return back()->with('success', 'Maintenance record updated successfully.');
    }
}
