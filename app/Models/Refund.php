<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Refund extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function return() { return $this->belongsTo(Returns::class, 'return_id');}
}
