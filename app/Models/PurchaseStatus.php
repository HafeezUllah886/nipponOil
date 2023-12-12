<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseStatus extends Model
{
    use HasFactory;
    protected $primaryKey = 'purchaseStatusID';
    protected $table = 'purchaseStatuses';
    protected $guarded = [];

    public function purchase()
    {
        return $this->hasMany(\App\Models\Purchase::class, 'purchaseStatusID', 'purchaseStatusID');
    }

}
