<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class advances extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function emp()
    {
        return $this->belongsTo(employees::class, 'empID', 'id');
    }

    public function payments(){
        return $this->hasMany(advancePayments::class, 'advanceID', 'id');
    }
}
