<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderProfile extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'service_category',
        'description',
        'experience_years',
        'location',
        'price_range',
        'is_verified'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
