<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quotation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function warehouse(){
        return $this->belongsTo(Warehouse::class, "warehouseID");
    }

    public function detail()
    {
        return $this->hasMany(quotation_details::class, "quotationID", 'id');
    }
}
