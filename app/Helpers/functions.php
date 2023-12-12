<?php

use App\Models\Reference;
use App\Models\Transaction;
use App\Models\Unit;

function getRef(){
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


function addTransaction($accountID, $date, $type, $credit, $debt, $refID, $desc){
    Transaction::create([
        'accountID' => $accountID,
        'date' => $date,
        'type' => $type,
        'credit' => $credit,
        'debt' => $debt,
        'refID' => $refID,
        'description' => $desc,
        'createdBy' => auth()->user()->email,
    ]);
}

function getAccountBalance($id)
{
    $cr = Transaction::where('accountID', $id)->sum('credit');
    $db = Transaction::where('accountID', $id)->sum('debt');

    return $cr - $db;
}

function getInitials($string) {
    $words = explode(' ', $string);
    $initials = '';

    if (count($words) === 1) {
        $initials = substr($words[0], 0, 3);
    } else {
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        // Limit the initials to 3 characters
        $initials = substr($initials, 0, 3);
    }

    return $initials;
}

