<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $primaryKey = 'saleID';
    protected $table = 'sales';
    protected $guarded = [];

    public function saleOrders()
    {
        return $this->hasMany(\App\Models\SaleOrder::class, 'saleID', 'saleID');
    }
    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'customerID', 'accountID');
    }

    public function salePayments()
    {
        return $this->hasMany(\App\Models\SalePayment::class, 'saleID', 'saleID');
    }

    public function saleReceive()
    {
        return $this->hasMany(\App\Models\SaleDelivered::class, 'saleID', 'saleID');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouseID');
    }
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'productID', 'productID');
    }

    public function saleDelivereds(){
        return $this->hasMany(SaleDelivered::class, 'saleID');
    }

    public function salesMan()
    {
        return $this->belongsTo(employees::class, 'salesManID', 'id');
    }

}
