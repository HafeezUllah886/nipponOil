<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalDeposit extends Model
{
    use HasFactory;
    protected $primaryKey = 'withdrawalDepositID';
    protected $table = 'withdrawalDeposits';
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'accountID', 'accountID');
    }
}
