<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;
    protected $table = 'returns';
    protected $guarded = [];

    public function order() { return $this->belongsTo(Order::class, 'order_id'); }
    public function refund() { return $this->hasOne(Refund::class, 'return_id');}
}
