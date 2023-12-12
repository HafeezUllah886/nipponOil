<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;
    protected $primaryKey = 'saleOrderID';
    protected $table = 'saleOrders';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'productID', 'productID');
    }

    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'saleUnit', 'unitID');
    }

    public function warehouse()
    {
        return $this->belongsTo(\App\Models\Warehouse::class, 'warehouseID', 'warehouseID');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'saleID');
    }

    public function salesMan()
    {
        return $this->belongsTo(employees::class, 'salesManID', 'id');
    }

}
