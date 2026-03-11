<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Show only properties belonging to this user's company
        $properties = Property::where('company_id', $user->company_id)->get();
        return view('property.index', compact('properties'));
    }

    public function create()
    {
        return view('property.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $user = Auth::user();
        if (!$user->company_id) {
            return redirect()->back()->withErrors('You must belong to a company to add a property.');
        }

        Property::create([
            'company_id' => $user->company_id,
            'name' => $request->name,
            'address' => $request->address,
            'description' => $request->description,
        ]);

        return redirect()->route('dashboard')->with('success', 'Property registered successfully.');
    }
}
