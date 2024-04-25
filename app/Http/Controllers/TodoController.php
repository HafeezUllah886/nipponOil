<?php

namespace App\Http\Controllers;

use App\Models\notifications;
use App\Models\todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(){

        $todos = todo::whereDate('due', '<', now())->get();
        foreach ($todos as $todo) {
            // Create a notification for each expired todo
            $check = notifications::where('refID', $todo->refID)->count();
            if($check == 0){
                notifications::create([
                    'warehouseID' => $todo->warehouseID,
                    'content' => $todo->title,
                    'date' => $todo->due,
                    'level' => $todo->level,
                    'refID' => $todo->refID,
                ]);
            }

        }

        $todos = todo::where('warehouseID', auth()->user()->warehouseID)->orderBy('id', 'desc')->get();
        $trasheds = todo::where('warehouseID', auth()->user()->warehouseID)->orderBy('id', 'desc')->onlyTrashed()->get();
        return view('todo.todo', compact('todos', 'trasheds'));
    }
    public function store(request $req)
    {
        $ref = getRef();
        todo::create(
            [
                'title' => $req->title,
                'notes' => $req->notes,
                'level' => "medium",
                'status' => "normal",
                'due' => $req->due,
                'warehouseID' => auth()->user()->warehouseID,
                'refID' => $ref
            ]
        );

        return "saved";
    }

    public function status($id, $status)
    {
        $todo = todo::find($id);
        if($status == 'done')
        {
            if($todo->status == 'done')
            {
                $todo->status = 'normal';
            }
            else{
                $todo->status = 'done';
            }
        }
        else{
            $todo->status = $status;
        }

        $todo->save();

        return back();
    }

    public function level($id, $level)
    {
        $todo = todo::where('id', $id)->withTrashed()->first();
        $todo->level = $level;
        $todo->save();

        return back();
    }

    public function delete($id)
    {
        todo::find($id)->delete();
        return back();
    }

    public function forceDelete($id)
    {
        $todo = todo::where('id', $id)->forceDelete();
        return back();
    }

    public function restore($id)
    {
        $todo = todo::where('id', $id)->restore();
        return back();
    }

    public function update(request $req)
    {
        $todo = todo::find($req->id);
        $todo->title = $req->title;
        $todo->notes = $req->notes;
        $todo->due = $req->due;
        $todo->save();

        return "updated";
    }
}
