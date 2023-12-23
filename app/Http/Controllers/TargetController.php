<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Sale;
use App\Models\target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TargetController extends Controller
{
    public function target()
    {
        $targets = target::orderBy('id', 'desc')->get();
        foreach($targets as $target)
        {
            if(now() > $target->endDate)
            {
                $target->status = "Closed";
                $target->save();
            }
        }
        return view('target.index', compact('targets'));
    }

    public function store(request $req)
    {
        target::create(
            [
                'startDate' => $req->startDate,
                'endDate' => $req->endDate,
                'type' => $req->type,
                'status' => "Active",
                'notes' => $req->notes
            ]
        );

        return back()->with("message", "Target Created");
    }

    public function update(request $req)
    {
        target::find($req->id)->update(
            [
                'startDate' => $req->startDate,
                'endDate' => $req->endDate,
                'type' => $req->type,
                'notes' => $req->notes,
            ]
        );

        return back()->with("message", "Target Updated");
    }

    public function view($id)
    {
        $target = target::find($id);
        if(!$target)
        {
            return back()->with("error", "Target Not Found");
        }

        $data = [];
        if($target->type == 'Sale')
        {
            $salesTotals = Sale::with('account')
            ->join('saleOrders', 'sales.saleID', '=', 'saleOrders.saleID')
            ->whereBetween('saleOrders.date', [$target->startDate, $target->endDate])
            ->select('sales.customerID', DB::raw('SUM(saleOrders.subtotal) AS total_sold'))
            ->groupBy('sales.customerID')
            ->get();

            foreach($salesTotals as $total)
            {
                if($total->customerID == 1)
                {
                    continue;
                }
                $data[] = [
                    "customerID" => $total->customerID,
                    "customerName" => $total->account->name,
                    "total" => $total->total_sold,
                ];
            }
        }
        else
        {
            $debitTotals = DB::table('accounts')
            ->join('transactions', 'accounts.accountID', '=', 'transactions.accountID')
            ->where('accounts.type', 'customer')
            ->whereBetween('transactions.date', [$target->startDate, $target->endDate])
            ->select('accounts.accountID', 'accounts.name', DB::raw('SUM(transactions.debt) AS total_debit'))
            ->groupBy('accounts.accountID')
            ->get();

            foreach($debitTotals as $total)
            {
                if($total->accountID == 1)
                {
                    continue;
                }
                $data[] = [
                    "customerID" => $total->accountID,
                    "customerName" => $total->name,
                    "total" => $total->total_debit,
                ];
            }
        }
        return view('target.view', compact('data'));
    }

    public function delete($id)
    {
        target::find($id)->delete();
        return back()->with('error', "Target Deleted");
    }
}
