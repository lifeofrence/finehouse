<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view('company.index', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_email' => 'required|email',
            'phone' => 'nullable|string'
        ]);

        $company = Company::create($request->all());

        // Assign the currently authenticated admin to this company
        $user = Auth::user();
        if ($user) {
            $user->company_id = $company->id;
            $user->role = 'admin'; // Promote to company admin
            $user->save();
        }

        return redirect()->route('dashboard')->with('success', 'Company created successfully.');
    }
}
