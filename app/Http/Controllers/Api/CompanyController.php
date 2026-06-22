<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
    {
        // For super admins, show all companies. For others, show their own company.
        $user = Auth::user();
        if ($user->role === 'super_admin') {
            $companies = Company::all();
        } else {
            $companies = Company::where('id', $user->company_id)->get();
        }
        return response()->json(compact('companies'));
    }

    public function create()
    {
        return response()->json(['message' => 'View endpoint reached']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'required|string',
            'contact_email' => 'required|email',
            'phone' => 'nullable|string'
        ]);

        $data = $request->all();
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('company_logos', 'public');
        }

        $company = Company::create($data);

        // Assign the currently authenticated admin to this company
        $user = Auth::user();
        if ($user) {
            $user->company_id = $company->id;
            // Only promote to admin if they don't have a role or are a regular user
            if (in_array($user->role, ['user', null])) {
                $user->role = 'admin';
            }
            $user->save();
        }

        return response()->json(['message' => 'Company created successfully.']);
    }

    public function edit(Company $company)
    {
        $user = Auth::user();
        if ($user->role !== 'super_admin' && $user->company_id !== $company->id) {
            abort(403);
        }
        return response()->json(compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $user = Auth::user();
        if ($user->role !== 'super_admin' && $user->company_id !== $company->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'required|string',
            'contact_email' => 'required|email',
            'phone' => 'nullable|string'
        ]);

        $data = $request->all();
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('company_logos', 'public');
        }

        $company->update($data);

        return response()->json(['message' => 'Company updated successfully.']);
    }

    public function activityLog()
    {
        $user = Auth::user();
        $query = \Spatie\Activitylog\Models\Activity::latest();

        if ($user->role !== 'super_admin') {
            $query->where('company_id', $user->company_id);
        }

        $activities = $query->paginate(20);
        return response()->json(compact('activities'));
    }
}
