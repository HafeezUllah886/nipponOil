<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    use HasFactory;
    protected $primaryKey = 'salePaymentID';
    protected $table = 'salePayments';
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'accountID', 'accountID');
    }
}
