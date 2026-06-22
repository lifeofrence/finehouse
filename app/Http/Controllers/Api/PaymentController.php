<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use App\Models\Room;
use App\Models\Property;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $search = $request->input('search');
        
        $query = Payment::with(['user', 'property', 'room', 'verifier']);

        if ($user->role === 'tenant') {
            $query->where(function($q) use ($user) { $q->where('user_id', $user->id); });
        } else {
            // Admin, landlord, accountant see payments for their company properties
            $propertyIds = Property::where('company_id', $user->company_id)->pluck('id');
            $query->whereIn('property_id', $propertyIds->toArray());
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        return response()->json(compact('payments'));
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role !== 'tenant') {
            abort(403);
        }

        // Tenants MUST have an active room assignment to use this page
        $booking = $user->bookings()
            ->whereIn('status', ['approved', 'confirmed'])
            ->latest()
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'You need an active or approved room booking to make a payment.']);
        }

        return response()->json(['room' => $booking->room]);
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

        $payment_year = date('Y');

        $room = Room::findOrFail($request->room_id);

        $payment_period = $payment_year . '-01-01';

        if (!$request->is_external) {
            // Online Payment via Paystack
            $paystack = new PaystackService();
            $reference = 'PAY_' . Auth::id() . '_' . time();

            $data = [
                'amount' => $request->amount * 100, // Paystack uses Kobo
                'email' => Auth::user()->email,
                'reference' => $reference,
                'callback_url' => route('payment.callback'),
                'metadata' => [
                    'room_id' => $room->id ?? null,
                    'payment_year' => $payment_year,
                    'user_id' => Auth::id(),
                ]
            ];

            Log::info('Initializing Paystack with data: ', $data);
            $initialization = $paystack->initializeTransaction($data);

            if (!$initialization || !$initialization['status']) {
                return back()->withErrors('Could not initialize Paystack payment. Please try again.');
            }

            // Create the pending payment record
            Payment::create([
                'user_id' => Auth::id(),
                'property_id' => $room->property_id ?? null,
                'room_id' => $room->id ?? null,
                'amount' => $request->amount,
                'reference' => $reference,
                'payment_period' => $payment_period,
                'status' => 'pending',
                'is_external' => false,
            ]);

            // Redirect to Paystack
            return response()->json(['message' => 'Redirected']);
        }

        $payment = new Payment([
            'user_id' => Auth::id(),
            'property_id' => $room->property_id ?? null,
            'room_id' => $room->id ?? null,
            'amount' => $request->amount,
            'payment_period' => $payment_period,
            'status' => 'pending',
            'is_external' => true,
        ]);

        if ($request->is_external && $request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('payment_proofs', 'public');
            $payment->external_proof_path = $path;
        }

        $payment->save();

        return response()->json(['message' => 'Payment submitted successfully. Awaiting verification.']);
    }

    public function handleGatewayCallback(Request $request)
    {
        $reference = $request->get('reference');
        if (!$reference) {
            return redirect()->route('payments.index')->withErrors('No reference provided.');
        }

        $paystack = new PaystackService();
        $response = $paystack->verifyTransaction($reference);

        if ($response && $response['status'] && $response['data']['status'] === 'success') {
            $payment = Payment::where('reference', $reference)->first();

            if ($payment && $payment->status !== 'verified') {
                $this->processSuccessfulPayment($payment);
                return response()->json(['message' => 'Payment successful and verified!']);
            }
        }

        return redirect()->route('payments.index')->withErrors('Payment verification failed.');
    }

    public function verify(Payment $payment)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'accountant', 'caretaker'])) {
            abort(403);
        }

        $this->processSuccessfulPayment($payment, $user->id);

        return back()->with('success', 'Payment verified successfully.');
    }

    private function processSuccessfulPayment(Payment $payment, $verifierId = null)
    {
        $payment->update([
            'status' => 'verified',
            'verified_by' => $verifierId
        ]);

        // Update Wallet Balance & Rent Expiry
        $tenantProfile = $payment->user->tenantProfile;
        if ($tenantProfile && $payment->room) {
            $tenantProfile->increment('wallet_balance', $payment->amount);
            $roomPrice = $payment->room->price;

            // Consume wallet balance to extend rent if it covers the full price
            while ($tenantProfile->wallet_balance >= $roomPrice && $roomPrice > 0) {
                if (!$tenantProfile->rent_commencement_date) {
                    $tenantProfile->rent_commencement_date = now();
                    $tenantProfile->rent_expiry_date = now()->addYear();
                } else {
                    $tenantProfile->rent_expiry_date = \Carbon\Carbon::parse($tenantProfile->rent_expiry_date)->addYear();
                }

                $tenantProfile->wallet_balance -= $roomPrice;
                $tenantProfile->save();
            }
        }
    }

    public function manualCreate()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'landlord', 'accountant', 'caretaker'])) {
            abort(403);
        }

        // Get properties managed by this user's company
        $propertyIds = Property::where('company_id', $user->company_id)->pluck('id');
        
        // Find tenants who have an approved or confirmed booking for these properties
        $rooms = Room::with(['property', 'bookings.user'])
            ->whereIn('property_id', $propertyIds->toArray())
            ->whereHas('bookings', function($q) {
                $q->whereIn('status', ['approved', 'confirmed']);
            })->get();

        return response()->json(compact('rooms'));
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
            'payment_year' => 'required|integer|min:2000|max:2100',
        ]);

        $room = Room::findOrFail($request->room_id);
        
        // Find the tenant who has the approved or confirmed booking for this room
        $booking = $room->bookings()->whereIn('status', ['approved', 'confirmed'])->latest()->first();
        if (!$booking) {
            return back()->withErrors('No approved or confirmed tenant found for this room.');
        }

        $payment = new Payment([
            'user_id' => $booking->user_id, // The tenant
            'property_id' => $room->property_id,
            'room_id' => $room->id,
            'amount' => $request->amount,
            'payment_period' => $request->payment_year . '-01-01',
            'status' => 'verified', // Manually added by admin/accountant, so it's already verified
            'is_external' => true,
            'verified_by' => $user->id,
        ]);

        $payment->save();

        $this->processSuccessfulPayment($payment, $user->id);

        return response()->json(['message' => 'Manual payment recorded successfully.']);
    }
}
