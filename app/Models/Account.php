<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $primaryKey = 'accountID';
    protected $table = 'accounts';
    protected $guarded = [];

    public function fromAccounts()
    {
        return $this->hasMany(\App\Models\Account::class, 'fromAccountID', 'accountID');
    }

    public function toAccounts()
    {
        return $this->hasMany(\App\Models\Account::class, 'toAccountID', 'accountID');
    }

    public function withdrawalDeposits()
    {
        return $this->hasMany(\App\Models\WithdrawalDeposit::class, 'accountID', 'accountID');
    }

    public function salePayments()
    {
        return $this->hasMany(\App\Models\SalePayment::class, 'accountID', 'accountID');
    }

    public function expenses()
    {
        return $this->hasMany(\App\Models\Expense::class, 'accountID', 'accountID');
    }

    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class, 'accountID', 'accountID');
    }

    public function purchaseReturns()
    {
        return $this->hasMany(\App\Models\PurchaseReturn::class, 'accountID', 'accountID');
    }

    public function saleReturns()
    {
        return $this->hasMany(\App\Models\SaleReturn::class, 'accountID', 'accountID');
    }

    public function purchases()
    {
        return $this->hasMany(\App\Models\Purchase::class, 'supplierID', 'accountID');
    }
    public function warehouse(){
        return $this->hasOne(Warehouse::class, 'warehouseID', 'warehouseID');
    }



}
