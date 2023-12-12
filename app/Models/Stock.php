<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $primaryKey = 'stockID';
    protected $table = 'stocks';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productID', 'productID');
    }

    public function warehouse()
    {
        return $this->belongsTo(\App\Models\Warehouse::class, 'warehouseID');
    }
}
