<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reconditioned extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product(){
        return $this->belongsTo(Product::class, "productID");
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class, "warehouseID");
    }

    public function obsolete(){
        return $this->belongsTo(obsolete::class, "id", "obsoleteID");
    }

    public function account(){
        return $this->belongsTo(Account::class, "accountID");
    }
}
