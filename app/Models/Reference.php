<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;
    protected $primaryKey = 'refID';
    protected $table = 'references';
    protected $guarded = [];
    public $timestamps = false;

    public static function getRef(){
        $ref = Reference::first();
        if($ref){
            $ref->ref = $ref->ref + 1;
        }
        else{
            $ref = new Reference();
            $ref->ref = 1;
        }
        $ref->save();
        return $ref->ref;
    }

    public  static function addTransaction($accountID, $date, $type, $credit, $debt, $refID, $desc){
        Transaction::create([
            'accountID' => $accountID,
            'date' => $date,
            'type' => $type,
            'credit' => $credit,
            'debt' => $debt,
            'refID' => $refID,
            'description' => $desc
        ]);
    }
}
