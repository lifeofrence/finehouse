<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TenantProfile;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantController extends Controller
{
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tenant_full_import_template.csv"',
        ];

        $columns = [
            'Full Name',
            'Email',
            'Phone',
            'Type (student/individual)',
            'Gender (male/female)',
            'DOB (YYYY-MM-DD)',
            'Religion',
            'State of Origin',
            'LGA',
            'Next of Kin',
            'Next of Kin Phone',
            'University (Student only)',
            'Matric Number (Student only)',
            'Level (Student only)',
            'Department (Student only)',
            'Faculty (Student only)',
            'Course (Student only)',
            'Address (Individual only)',
            'Rent Commencement Date (YYYY-MM-DD)',
            'Rent Expiry Date (YYYY-MM-DD)',
            'Property ID (Leave empty for unassigned)',
        ];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadOnboardingTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bulk_onboarding_template.csv"',
        ];

        $columns = ['Full Name', 'Email', 'Phone Number', 'Property ID (Optional)'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120', // 5MB max
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        // Skip header row
        fgetcsv($handle);
        
        $importedCount = 0;
        $errors = [];
        $admin = Auth::user();

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 3) {
                continue; // Skip empty/malformed rows
            }

            $name = $row[0];
            $email = $row[1];
            $phone = $row[2];

            // Check if user already exists
            if (User::where('email', $email)->exists()) {
                $errors[] = "Skip: Email {$email} already registered.";
                continue;
            }

            try {
                // Create User
                $user = User::create([
                    'name' => $name,
                    'email' => strtolower($email),
                    'password' => \Illuminate\Support\Facades\Hash::make('password123'), // Default password
                    'role' => 'tenant',
                    'company_id' => $admin->company_id, // Scope to admin's company
                    'property_id' => $row[20] ?? $row[3] ?? null, // Map from full or simple template
                ]);

                $user->email_verified_at = now();
                $user->save();

                // Create Profile
                $user->tenantProfile()->create([
                    'type' => strtolower($row[3] ?? 'individual'),
                    'phone_number' => $phone,
                    'gender' => strtolower($row[4] ?? ''),
                    'dob' => $row[5] ?? null,
                    'religion' => $row[6] ?? '',
                    'state_of_origin' => $row[7] ?? '',
                    'lga' => $row[8] ?? '',
                    'next_of_kin' => $row[9] ?? '',
                    'next_of_kin_phone' => $row[10] ?? '',
                    'university' => $row[11] ?? '',
                    'matric_number' => $row[12] ?? '',
                    'level' => $row[13] ?? '',
                    'department' => $row[14] ?? '',
                    'faculty' => $row[15] ?? '',
                    'course' => $row[16] ?? '',
                    'address' => $row[17] ?? '',
                    'rent_commencement_date' => $row[18] ?? null,
                    'rent_expiry_date' => $row[19] ?? null,
                ]);

                $importedCount++;
            } catch (\Exception $e) {
                $errors[] = "Error importing {$email}: " . $e->getMessage();
            }
        }

        fclose($handle);

        $message = "Successfully imported {$importedCount} tenants.";
        if (!empty($errors)) {
            return back()->with('success', $message)->withErrors($errors);
        }

        return back()->with('success', $message);
    }
    public function create()
    {
        $user = Auth::user();
        $properties = $user->role === 'super_admin' 
            ? Property::all() 
            : Property::where('company_id', $user->company_id)->get();
            
        return view('admin.tenants.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => \Illuminate\Support\Facades\Hash::make('password123'), // Default password as per request logic
            'role' => 'tenant',
            'company_id' => $admin->company_id,
        ]);

        $user->email_verified_at = now();
        $user->save();

        // Initialize profile
        $user->tenantProfile()->create([
            'type' => 'student' // Default type
        ]);

        return redirect()->route('admin.tenants.index')->with('success', "Tenant account created for {$user->name}. They can now login with 'password123' and complete their profile.");
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $propertyId = $request->get('property_id');
        $search = $request->get('search');
        
        $query = User::where('role', 'tenant')
            ->with(['tenantProfile', 'bookings.room.property']);

        // Filter by company for non-super-admins
        if ($user->role !== 'super_admin') {
            $query->where('company_id', $user->company_id);
        }

        if ($propertyId) {
            $query->whereHas('bookings.room.property', function ($q) use ($propertyId) {
                $q->where('id', $propertyId);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('tenantProfile', function ($profileQ) use ($search) {
                      $profileQ->where('phone_number', 'like', "%{$search}%");
                  });
            });
        }
        
        $tenants = $query->paginate(15);

        // Fetch properties for the filter
        if ($user->role === 'super_admin') {
            $properties = Property::all();
        } else {
            $properties = Property::where('company_id', $user->company_id)->get();
        }

        return view('admin.tenants.index', compact('tenants', 'properties', 'propertyId', 'search'));
    }

    public function show(User $tenant)
    {
        $user = Auth::user();
        if ($tenant->role !== 'tenant') {
            abort(404);
        }

        // Authorization check
        if ($user->role !== 'super_admin') {
            $isAuthorized = $tenant->bookings()->whereHas('room.property', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->exists();
            
            if (!$isAuthorized) {
                abort(403);
            }
        }

        $tenant->load(['tenantProfile', 'bookings.room.property', 'payments' => function($q) {
            $q->orderBy('created_at', 'desc');
        }]);

        $daysLeft = null;
        if ($tenant->tenantProfile && $tenant->tenantProfile->rent_expiry_date) {
            $expiry = \Carbon\Carbon::parse($tenant->tenantProfile->rent_expiry_date);
            $daysLeft = (int) now()->diffInDays($expiry, false);
        }
        
        return view('admin.tenants.show', compact('tenant', 'daysLeft'));
    }

    public function rentDetails(User $tenant)
    {
        $user = Auth::user();
        if ($tenant->role !== 'tenant') {
            abort(404);
        }

        // Authorization check
        if ($user->role !== 'super_admin') {
            $isAuthorized = $tenant->bookings()->whereHas('room.property', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->exists();
            
            if (!$isAuthorized) {
                abort(403);
            }
        }

        $tenant->load(['tenantProfile', 'bookings.room.property', 'payments.verifier']);
        
        // Basic financial calculations
        $totalPaid = $tenant->payments->where('status', 'verified')->sum('amount');
        
        $activeBooking = $tenant->bookings->where('status', 'confirmed')->first();
        $annualRent = $activeBooking ? $activeBooking->room->price : 0;
        
        $leaseMonths = 0;
        $totalLeaseValue = 0;
        if ($tenant->tenantProfile && $tenant->tenantProfile->rent_commencement_date && $tenant->tenantProfile->rent_expiry_date) {
            $start = \Carbon\Carbon::parse($tenant->tenantProfile->rent_commencement_date);
            $end = \Carbon\Carbon::parse($tenant->tenantProfile->rent_expiry_date);
            $leaseMonths = $start->diffInMonths($end) ?: 1;
            
            // Total lease value based on annual rate
            // If it's a 12-month lease, it's exactly the annual rent
            $totalLeaseValue = ($leaseMonths / 12) * $annualRent;
        }

        $balance = $totalLeaseValue - $totalPaid;
        $walletBalance = $tenant->tenantProfile->wallet_balance ?? 0;

        return view('admin.tenants.rent-details', compact('tenant', 'totalPaid', 'annualRent', 'leaseMonths', 'totalLeaseValue', 'balance', 'walletBalance'));
    }

    public function edit(User $tenant)
    {
        $user = Auth::user();
        if ($tenant->role !== 'tenant') {
            abort(404);
        }

        // Authorization check
        if ($user->role !== 'super_admin' && $tenant->company_id !== $user->company_id) {
            abort(403);
        }

        $tenant->load('tenantProfile');
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, User $tenant)
    {
        $user = Auth::user();
        if ($tenant->role !== 'tenant') {
            abort(404);
        }

        // Authorization check
        if ($user->role !== 'super_admin' && $tenant->company_id !== $user->company_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $tenant->id,
            'phone_number' => 'nullable|string|max:20',
            'type' => 'required|in:student,individual',
            'gender' => 'nullable|in:male,female',
            'dob' => 'nullable|date',
            'religion' => 'nullable|string|max:100',
            'state_of_origin' => 'nullable|string|max:100',
            'lga' => 'nullable|string|max:100',
            'university' => 'nullable|string|max:255',
            'faculty' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'course' => 'nullable|string|max:100',
            'matric_number' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:10',
            'address' => 'nullable|string',
            'next_of_kin' => 'nullable|string|max:255',
            'next_of_kin_phone' => 'nullable|string|max:20',
        ]);

        $tenant->update([
            'name' => $request->name,
            'email' => strtolower($request->email),
        ]);

        $tenant->tenantProfile()->update($request->only([
            'type', 'phone_number', 'gender', 'dob', 'religion',
            'state_of_origin', 'lga', 'university', 'faculty',
            'department', 'course', 'matric_number', 'level',
            'address', 'next_of_kin', 'next_of_kin_phone'
        ]));

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant information updated successfully.');
    }

    public function updateDates(Request $request, User $tenant)
    {
        $user = Auth::user();
        if ($user->role !== 'super_admin') {
             $isAuthorized = $tenant->bookings()->whereHas('room.property', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->exists();
            
            if (!$isAuthorized) {
                abort(403);
            }
        }

        $request->validate([
            'rent_commencement_date' => 'required|date',
            'rent_expiry_date' => 'required|date|after:rent_commencement_date',
        ]);

        $tenant->tenantProfile()->update([
            'rent_commencement_date' => $request->rent_commencement_date,
            'rent_expiry_date' => $request->rent_expiry_date,
        ]);

        return back()->with('success', 'Rent dates updated successfully.');
    }

    public function updateWallet(Request $request, User $tenant)
    {
        $user = Auth::user();
        if ($user->role !== 'super_admin') {
             $isAuthorized = $tenant->bookings()->whereHas('room.property', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->exists();
            
            if (!$isAuthorized) {
                abort(403);
            }
        }

        $request->validate([
            'wallet_balance' => 'required|numeric|min:0',
        ]);

        $tenant->tenantProfile()->update([
            'wallet_balance' => $request->wallet_balance,
        ]);

        return back()->with('success', 'Wallet balance updated successfully.');
    }

    public function destroy(User $tenant)
    {
        $user = Auth::user();
        if ($tenant->role !== 'tenant') {
            abort(403);
        }

        if ($user->role !== 'super_admin') {
             $isAuthorized = $tenant->company_id === $user->company_id;
            
            if (!$isAuthorized) {
                abort(403);
            }
        }

        $tenant->delete();

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant deleted successfully.');
    }

    public function resetPassword(User $tenant)
    {
        $user = Auth::user();
        if ($tenant->role !== 'tenant') {
            abort(403);
        }

        if ($user->role !== 'super_admin') {
             $isAuthorized = $tenant->company_id === $user->company_id;
            
            if (!$isAuthorized) {
                abort(403);
            }
        }

        $tenant->update([
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        return back()->with('success', "Password for {$tenant->name} has been reset to 'password123'.");
    }
}
