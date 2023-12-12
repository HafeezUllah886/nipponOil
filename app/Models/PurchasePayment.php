<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{
    use HasFactory;
    protected $primaryKey = 'purchasePaymentID';
    protected $table = 'purchasePayments';
    protected $guarded = [];

    public function purchase()
    {
        return $this->belongsTo(\App\Models\Purchase::class, 'purchaseID', 'purchaseID');
    }
    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'accountID', 'accountID');

    }
}
