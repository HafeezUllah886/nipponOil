<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employees extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function attendance()
    {
        return $this->hasMany(attendance::class, 'id', 'empID');
    }
}
