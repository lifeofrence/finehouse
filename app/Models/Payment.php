<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'property_id',
        'room_id',
        'amount',
        'reference',
        'payment_period',
        'status',
        'is_external',
        'external_proof_path',
        'verified_by',
    ];

    protected $casts = [
        'payment_period' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
