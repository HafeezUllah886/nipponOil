<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $primaryKey = 'expenseID';
    protected $table = 'expenses';
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'accountID', 'accountID');
    }

    public function category(){
        return $this->belongsTo(ExpenseCategory::class, 'expenseCategoryID');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, "warehouseID");
    }
}
