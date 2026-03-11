<?php

namespace App\Http\Controllers;

use App\Models\TenantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

class TenantProfileController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'tenant') {
            abort(403);
        }

        $profile = $user->tenantProfile;
        return view('tenant.profile', compact('profile'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'type' => 'required|in:student,ordinary',
            'phone_number' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'required|date',
            'religion' => 'required|string',
            'state_of_origin' => 'required|string',
            'lga' => 'required|string',
            'next_of_kin' => 'required|string',
            'next_of_kin_phone' => 'required|string',
            'passport_image' => 'nullable|image|max:2048',
        ];

        if ($request->type === 'student') {
            $rules['matric_number'] = 'required|string';
            $rules['level'] = 'required|string';
            $rules['department'] = 'required|string';
            $rules['faculty'] = 'required|string';
            $rules['course'] = 'required|string';
            $rules['university'] = 'required|string';
        } else {
            $rules['address'] = 'required|string';
        }

        $validated = $request->validate($rules);

        $data = [
            'type' => $request->type,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'religion' => $request->religion,
            'state_of_origin' => $request->state_of_origin,
            'lga' => $request->lga,
            'next_of_kin' => $request->next_of_kin,
            'next_of_kin_phone' => $request->next_of_kin_phone,
        ];

        if ($request->hasFile('passport_image')) {
            $path = $request->file('passport_image')->store('passports', 'public');
            $data['passport'] = $path;
        }

        if ($request->type === 'student') {
            $data = array_merge($data, [
                'matric_number' => $request->matric_number,
                'level' => $request->level,
                'department' => $request->department,
                'faculty' => $request->faculty,
                'course' => $request->course,
                'university' => $request->university,
                'address' => null,
            ]);
        } else {
            $data = array_merge($data, [
                'matric_number' => null,
                'level' => null,
                'department' => null,
                'faculty' => null,
                'course' => null,
                'university' => null,
                'address' => $request->address,
            ]);
        }

        TenantProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully.');
    }
}
