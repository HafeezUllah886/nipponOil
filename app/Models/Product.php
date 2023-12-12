<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'productID';
    protected $table = 'products';
    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class, 'brandID', 'brandID');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'categoryID', 'categoryID');
    }

    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'productUnit', 'unitID');
    }

public function stocks(){
    return $this->hasMany(Stock::class, 'productID', 'productID');
}

    public function purchaseOrders()
    {
        return $this->hasMany(purchaseOrder::class, 'productID', 'productID');
    }

    public function saleOrders()
    {
        return $this->hasMany(saleOrder::class, 'productID', 'productID');
    }

    public function purchaseReturnDetails()
    {
        return $this->hasMany(purchaseReturnDetail::class, 'productID', 'productID');
    }
    public function saleReturnDetails()
    {
        return $this->hasMany(SaleReturnDetail::class, 'productID', 'productID');
    }
}
