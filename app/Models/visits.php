<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class visits extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, "warehouseID");
    }

    public function expAccount()
    {
        return $this->belongsTo(Account::class, "account", "accountID");
    }

    public function employee()
    {
        return $this->belongsTo(employees::class, "visit_by", "id");
    }
}
