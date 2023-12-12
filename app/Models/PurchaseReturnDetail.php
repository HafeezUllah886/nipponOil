<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnDetail extends Model
{
    use HasFactory;
    protected $primaryKey = 'purchaseReturnDetailID';
    protected $table = 'purchaseReturnDetails';
    protected $guarded = [];
    public $timestamps = false;

    public function purchaseReturn()
    {
        return $this->belongsTo(\App\Models\PurchaseReturn::class, 'purchaseReturnID', 'purchaseReturnID');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'productID', 'productID');
    }

}
