<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReceive extends Model
{
    use HasFactory;
    protected $primaryKey = 'purchaseReceiveID';
    protected $table = 'purchaseReceives';
    protected $guarded = [];
    public $timestamps = false;

    public function purchase()
    {
        return $this->belongsTo(\App\Models\Purchase::class, 'purchaseID', 'purchaseID');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'productID', 'productID');
    }

    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'purchaseUnit', 'unitID');
    }
}
