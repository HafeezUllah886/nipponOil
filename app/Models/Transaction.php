<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $primaryKey = 'transactionID';
    protected $table = 'transactions';
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'accountID', 'accountID');
    }
}
