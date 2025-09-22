<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'start_date',
        'end_date',
        'discount_value',
        'min_purchase',
    ];

    // Relasi ke Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Method untuk cek validitas discount
    public function isValid()
    {
        $today = now()->toDateString();

        return (!$this->start_date || $this->start_date <= $today) &&
               (!$this->end_date || $this->end_date >= $today);
    }
}
