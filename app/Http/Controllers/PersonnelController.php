<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PersonnelController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'landlord') {
            abort(403, 'Unauthorized action.');
        }

        // Fetch properties belonging to the company to assign personnel to specific properties if needed
        $properties = $user->company->properties;
        return view('personnel.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:accountant,caretaker,lodge_president'],
            'property_id' => ['nullable', 'exists:properties,id']
        ]);

        $admin = Auth::user();

        // Create the user and link them to the admin's company
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'company_id' => $admin->company_id,
            'property_id' => $request->property_id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Personnel registered successfully.');
    }
}
