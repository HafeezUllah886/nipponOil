<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class obsolete extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function warehouse()
    {
        return $this->belongsTo(warehouse::class, "warehouseID");
    }

    public function product()
    {
        return $this->belongsTo(Product::class, "productID");
    }
}
