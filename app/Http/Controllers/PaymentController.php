<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Room;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'tenant') {
            $payments = Payment::with('property', 'room', 'verifier')->where('user_id', $user->id)->get();
        } else {
            // Admin, landlord, accountant see payments for their company properties
            $propertyIds = Property::where('company_id', $user->company_id)->pluck('id');
            $payments = Payment::with('user', 'property', 'room', 'verifier')
                ->whereIn('property_id', $propertyIds->toArray())
                ->get();
        }

        return view('payment.index', compact('payments'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }

        // Only allowing them to pay for a room they are assigned to (or have booked). 
        // For Phase 4, assuming they have an assigned/booked room.
        // Let's just grab the room they booked that is approved
        $booking = Auth::user()->bookings()->where('status', 'approved')->first();
        if (!$booking) {
            return redirect()->route('dashboard')->withErrors('You need an approved booking to make a payment.');
        }

        return view('payment.create', ['room' => $booking->room]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'amount' => 'required|numeric|min:0',
            'is_external' => 'required|boolean',
            'proof_image' => 'required_if:is_external,1|image|max:2048'
        ]);

        $room = Room::findOrFail($request->room_id);

        $payment = new Payment([
            'user_id' => Auth::id(),
            'property_id' => $room->property_id,
            'room_id' => $room->id,
            'amount' => $request->amount,
            'status' => 'pending',
            'is_external' => $request->is_external,
        ]);

        if ($request->is_external && $request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('payment_proofs', 'public');
            $payment->external_proof_path = $path;
        }

        $payment->save();

        return redirect()->route('payments.index')->with('success', 'Payment submitted successfully. Awaiting verification.');
    }

    public function verify(Payment $payment)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'accountant', 'caretaker'])) {
            abort(403);
        }

        $payment->update([
            'status' => 'verified',
            'verified_by' => $user->id
        ]);
        
        // Bonus: Record Rent Commencement and Expiry for TenantProfile
        $tenantProfile = $payment->user->tenantProfile;
        if ($tenantProfile) {
            $tenantProfile->update([
                'rent_commencement_date' => now(),
                'rent_expiry_date' => now()->addYear() // Assuming 1 year rent
            ]);
        }

        return back()->with('success', 'Payment verified successfully.');
    }

    public function manualCreate()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'accountant', 'caretaker'])) {
            abort(403);
        }

        // Get properties managed by this user's company
        $propertyIds = Property::where('company_id', $user->company_id)->pluck('id');
        
        // Find tenants who have an approved booking for these properties
        $rooms = Room::with('property')
            ->whereIn('property_id', $propertyIds->toArray())
            ->whereHas('bookings', function($q) {
                $q->where('status', 'approved');
            })->get();

        return view('payment.manual', compact('rooms'));
    }

    public function storeManual(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'accountant', 'caretaker'])) {
            abort(403);
        }

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $room = Room::findOrFail($request->room_id);
        
        // Find the tenant who has the approved booking for this room
        $booking = $room->bookings()->where('status', 'approved')->first();
        if (!$booking) {
            return back()->withErrors('No approved tenant found for this room.');
        }

        $payment = new Payment([
            'user_id' => $booking->user_id, // The tenant
            'property_id' => $room->property_id,
            'room_id' => $room->id,
            'amount' => $request->amount,
            'status' => 'verified', // Manually added by admin/accountant, so it's already verified
            'is_external' => true,
            'verified_by' => $user->id,
        ]);

        $payment->save();

        // Bonus: Update Rent Dates if needed
        $tenantProfile = $booking->user->tenantProfile;
        if ($tenantProfile) {
            $tenantProfile->update([
                'rent_commencement_date' => now(),
                'rent_expiry_date' => now()->addYear()
            ]);
        }

        return redirect()->route('payments.index')->with('success', 'Manual payment recorded successfully.');
    }
}
