<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payroll extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function emp()
    {
        return $this->belongsTo(employees::class, 'empID', 'id');
    }

    public function account()
    {
        return $this->belongsTo(account::class, 'accountID', 'accountID');
    }
}
