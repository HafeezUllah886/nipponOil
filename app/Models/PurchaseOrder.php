<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $primaryKey = 'purchaseOrderID';
    protected $table = 'purchaseOrders';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'productID', 'productID');
    }

    public function purchase()
    {
        return $this->belongsTo(\App\Models\Purchase::class, 'purchaseID', 'purchaseID');
    }

    public function warehouse()
    {
        return $this->belongsTo(\App\Models\Warehouse::class, 'warehouseID', 'warehouseID');
    }

    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'purchaseUnit', 'unitID');
    }

    public function purchaseOrder()
    {
        return $this->hasMany(purchaseOrder::class, 'purchaseID');
    }
}
