<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stockTransfer extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function from_warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from');
    }

    public function to_warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to');
    }
    public function details(){
        return $this->hasMany(stockTransferDetails::class, 'transferID');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'accountID');
    }
}
