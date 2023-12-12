<?php

namespace App\Http\Controllers;

use App\Models\employees;
use App\Models\empTransactions;
use Illuminate\Http\Request;

class empStatementController extends Controller
{
    public function index($id)
    {
        $emp = employees::find($id);

        return view('hrm.statement.statement', compact('emp'));
    }

    public function details($id, $from, $to)
    {
          /* $from = Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d');
        $to = Carbon::createFromFormat('d-m-Y', $to)->format('Y-m-d'); */
        $items = empTransactions::where('empID', $id)->where('date', '>=', $from)->where('date', '<=', $to)->get();
        $prev = empTransactions::where('empID', $id)->where('date', '<', $from)->get();

        $p_balance = 0;
        foreach ($prev as $item) {
            $p_balance += $item->credit;
            $p_balance -= $item->debt;
        }

        $all = empTransactions::where('empID', $id)->get();

        $c_balance = 0;
        foreach ($all as $item) {
            $c_balance += $item->credit;
            $c_balance -= $item->debt;
        }
        return view('hrm.statement.statment_details')->with(compact('items', 'p_balance', 'c_balance'));
    }
}
