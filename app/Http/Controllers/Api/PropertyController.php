<?php

namespace App\Http\Controllers\Api;

use App\Models\Property;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'super_admin') {
            $properties = Property::withCount([
                'rooms',
                'rooms as available_rooms_count' => function ($query) {
                    $query->where('is_available', true);
                }
            ])->get();
        } else {
            if (!$user->company_id) {
                abort(403, 'Company not assigned.');
            }
            
            $properties = Property::where('company_id', $user->company_id)
                ->withCount([
                    'rooms',
                    'rooms as available_rooms_count' => function ($query) {
                        $query->where('is_available', true);
                    }
                ])
                ->get();
        }
            
        return response()->json(compact('properties'));
    }

    public function create()
    {
        $companies = Auth::user()->role === 'super_admin' ? Company::all() : null;
        return response()->json(compact('companies'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string'
        ];

        if ($user->role === 'super_admin') {
            $rules['company_id'] = 'required|exists:companies,id';
        }

        $request->validate($rules);

        $companyId = $user->role === 'super_admin' ? $request->company_id : $user->company_id;

        if (!$companyId) {
            return redirect()->back()->withErrors('No company assigned or selected.');
        }

        Property::create([
            'company_id' => $companyId,
            'name' => $request->name,
            'address' => $request->address,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Property registered successfully.']);
    }

    public function edit(Property $property)
    {
        $user = Auth::user();
        if ($user->role !== 'super_admin' && $property->company_id !== $user->company_id) {
            abort(403);
        }
        
        $companies = $user->role === 'super_admin' ? Company::all() : null;
        return response()->json(compact('property', 'companies'));
    }

    public function update(Request $request, Property $property)
    {
        $user = Auth::user();
        if ($user->role !== 'super_admin' && $property->company_id !== $user->company_id) {
            abort(403);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string'
        ];

        if ($user->role === 'super_admin') {
            $rules['company_id'] = 'required|exists:companies,id';
        }

        $request->validate($rules);

        $property->update([
            'company_id' => $user->role === 'super_admin' ? $request->company_id : $property->company_id,
            'name' => $request->name,
            'address' => $request->address,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Property updated successfully.']);
    }

    public function destroy(Property $property)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'super_admin']) && $property->company_id !== $user->company_id) {
            abort(403);
        }

        $property->delete();

        return response()->json(['message' => 'Property deleted successfully.']);
    }
}
