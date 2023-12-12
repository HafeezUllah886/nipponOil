<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class todo extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function warehouse(){
        return $this->belongsTo(Warehouse::class, 'warehouseID');
    }
}
