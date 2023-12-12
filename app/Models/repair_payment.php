<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class repair_payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function repair()
    {
        return $this->belongsTo(repair::class, 'repairID', 'id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'accountID');
    }
}
