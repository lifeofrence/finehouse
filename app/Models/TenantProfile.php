<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantProfile extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'phone_number',
        'gender',
        'dob',
        'religion',
        'state_of_origin',
        'lga',
        'passport',
        'next_of_kin',
        'next_of_kin_phone',
        'matric_number',
        'level',
        'department',
        'faculty',
        'course',
        'university',
        'address',
        'rent_commencement_date',
        'rent_expiry_date',
        'wallet_balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
