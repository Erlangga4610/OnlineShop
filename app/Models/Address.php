<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\Comparator\HasFactory;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'street',
        'city',
        'province',
        'postal_code',
        'country',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
