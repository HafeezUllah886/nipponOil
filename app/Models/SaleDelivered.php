<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDelivered extends Model
{
    use HasFactory;
    protected $primaryKey = 'saleDeliveredID';
    protected $table = 'saleDelivered';
    protected $guarded = [];
    public $timestamps = false;

    function sale(){
        return $this->belongsTo(Sale::class, 'saleID');
    }

    function products(){
        return $this->hasMany(Product::class, 'productID');
    }
}
