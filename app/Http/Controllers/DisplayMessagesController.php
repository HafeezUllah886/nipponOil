<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\displayMessages;
use Illuminate\Http\Request;

class DisplayMessagesController extends Controller
{
    public function store(request $req){
        displayMessages::create(
            [
                'date' => $req->date,
                'msg' => $req->msg,
                'userID' => auth()->user()->id,
            ]
            );

            return redirect('/home')->with('message', "Message Displayed");
    }

    public function delete($id)
    {
        displayMessages::find($id)->delete();

        return redirect()->back()->with('error', "Message Deleted");
    }
}
