<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PersonnelController extends Controller
{
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="personnel_import_template.csv"',
        ];

        $columns = ['Full Name', 'Email', 'Role (accountant/caretaker/lodge_president)', 'Property ID (Optional)'];

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
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        fgetcsv($handle); // Skip header

        $importedCount = 0;
        $errors = [];
        $admin = Auth::user();

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 3) continue;

            $name = $row[0];
            $email = $row[1];
            $role = strtolower($row[2]);
            $propertyId = $row[3] ?? null;

            if (User::where('email', $email)->exists()) {
                $errors[] = "Skip: Email {$email} already exists.";
                continue;
            }

            if (!in_array($role, ['accountant', 'caretaker', 'lodge_president'])) {
                $errors[] = "Skip: Invalid role for {$email}.";
                continue;
            }

            try {
                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'role' => $role,
                    'company_id' => $admin->company_id,
                    'property_id' => $propertyId ?: null,
                ]);
                $importedCount++;
            } catch (\Exception $e) {
                $errors[] = "Error importing {$email}: " . $e->getMessage();
            }
        }

        fclose($handle);
        $message = "Imported {$importedCount} personnel.";
        return back()->with('success', $message)->withErrors($errors);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'admin', 'landlord'])) {
            abort(403, 'Unauthorized action.');
        }

        $propertyId = $request->get('property_id');
        $search = $request->get('search');
        $targetCompanyId = $user->role === 'super_admin' ? $request->get('company_id') : $user->company_id;

        $allowedRoles = ['accountant', 'caretaker', 'lodge_president'];
        if ($user->role === 'super_admin' || $user->role === 'admin') {
            $allowedRoles[] = 'admin';
            $allowedRoles[] = 'landlord';
        }

        $query = User::whereIn('role', $allowedRoles)
            ->with(['property', 'company']);

        if ($user->role !== 'super_admin' || $targetCompanyId) {
            $query->where('company_id', $targetCompanyId);
        }

        if ($propertyId) {
            $query->where('property_id', $propertyId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $personnel = $query->get();
        
        if ($user->role === 'super_admin') {
            $properties = Property::all();
            $companies = Company::all();
        } else {
            $properties = $user->company ? $user->company->properties : collect();
            $companies = collect();
        }

        return view('personnel.index', compact('personnel', 'properties', 'propertyId', 'search', 'companies', 'targetCompanyId'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'admin', 'landlord'])) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role === 'super_admin') {
            $properties = Property::all();
            $companies = Company::all();
        } else {
            $properties = $user->company ? $user->company->properties : collect();
            $companies = collect();
        }

        return view('personnel.create', compact('properties', 'companies'));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();
        
        $allowedRoles = ['accountant', 'caretaker', 'lodge_president'];
        if ($admin->role === 'super_admin') {
            $allowedRoles[] = 'admin';
            $allowedRoles[] = 'landlord';
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'property_id' => ['nullable', 'exists:properties,id']
        ];

        if ($admin->role === 'super_admin') {
            $rules['company_id'] = ['required', 'exists:companies,id'];
        }

        $request->validate($rules);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'company_id' => $admin->role === 'super_admin' ? $request->company_id : $admin->company_id,
            'property_id' => $request->property_id,
        ]);

        return redirect()->route('personnel.index')->with('success', 'Personnel registered successfully.');
    }

    public function edit(User $personnel)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'admin', 'landlord'])) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role !== 'super_admin' && $personnel->company_id !== $user->company_id) {
            abort(403);
        }

        if ($user->role === 'super_admin') {
            $properties = Property::all();
            $companies = Company::all();
        } else {
            $properties = $user->company ? $user->company->properties : collect();
            $companies = collect();
        }
        
        return view('personnel.edit', compact('personnel', 'properties', 'companies'));
    }

    public function update(Request $request, User $personnel)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'admin', 'landlord'])) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role !== 'super_admin' && $personnel->company_id !== $user->company_id) {
            abort(403);
        }

        $allowedRoles = ['accountant', 'caretaker', 'lodge_president'];
        if ($user->role === 'super_admin') {
            $allowedRoles[] = 'admin';
            $allowedRoles[] = 'landlord';
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$personnel->id],
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
            'property_id' => ['nullable', 'exists:properties,id']
        ];

        if ($user->role === 'super_admin') {
            $rules['company_id'] = ['required', 'exists:companies,id'];
        }

        $request->validate($rules);

        $personnel->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'company_id' => $user->role === 'super_admin' ? $request->company_id : $personnel->company_id,
            'property_id' => $request->property_id,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $personnel->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('personnel.index')->with('success', 'Personnel updated successfully.');
    }

    public function resetPassword(User $personnel)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'admin', 'landlord'])) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->role !== 'super_admin' && $personnel->company_id !== $user->company_id) {
            abort(403);
        }

        $personnel->update([
            'password' => Hash::make('password123'),
        ]);

        return back()->with('success', "Password for {$personnel->name} has been reset to 'password123'.");
    }
}
