<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attendance extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function emp()
    {
        return $this->belongsTo(employees::class, 'empID', 'id');
    }
}
