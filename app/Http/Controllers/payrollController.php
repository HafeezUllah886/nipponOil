<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\advances;
use App\Models\attendance;
use App\Models\employees;
use App\Models\empTransactions;
use App\Models\payroll;
use App\Models\SaleOrder;
use App\Models\SaleReturnDetail;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Gd\Shapes\CircleShape;

class payrollController extends Controller
{
    public function payroll(){
        if(auth()->user()->can('All Warehouses')){
            $payroll = payroll::orderBy('id', 'desc')->get();
        }
        else{
            $payroll = payroll::whereHas('emp', function ($query) {
                $query->where('warehouseID', auth()->user()->warehouseID);
            })->orderBy('id','desc')->get();
        }
        $accounts = Account::where('type', 'Business')->where('warehouseID', auth()->user()->warehouseID)->where('status', 'Active')->get();
        return view('hrm.payroll.index', compact('payroll', 'accounts'));
    }

    public function payrollGenerate(request $req){
       $emps = employees::where('status', 'Active')->where('warehouseID', auth()->user()->warehouseID)->get();
       $nos = 0;
       $month = $req->month. "-01";


       foreach($emps as $emp)
       {
        $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($req->month)), date('Y', strtotime($req->month)));
        $basicSalary = $emp->salary;
        $salaryPerDay = $emp->salary / $totalDaysInMonth;
        $check = payroll::where('empID', $emp->id)->where('month', $req->month)->count();
        $reqDate = date("Y-m-d", strtotime($month));
        if(date('m Y', strtotime($emp->doe)) > date("m Y", strtotime($req->month)))
        {
            continue;
        }
        else
        {
            if(date("m Y", strtotime($req->month)) == date('m Y', strtotime($emp->doe)))
            {
                // Assuming $emp->doe contains the date from the database in "Y-m-d" format
                $databaseDate = $emp->doe;

                // Convert the database date to a timestamp
                $timestamp = strtotime($databaseDate);

                // Calculate the last day of the same month
                $lastDayOfMonth = date('Y-m-t', $timestamp); // 't' gives the last day of the month

                // Calculate the difference in days
                $totalDaysInMonth = (strtotime($lastDayOfMonth) - $timestamp) / (60 * 60 * 24);

                $basicSalary = $salaryPerDay * $totalDaysInMonth;

            }

        }

        if($check > 0)
        {
            continue;
        }
        $nos += 1;
        $commission = SaleOrder::whereMonth('date', date('m', strtotime($req->month)))
        ->whereYear('date', date('Y', strtotime($req->month)))
        ->where('salesManID', $emp->id)->sum('commission');

        $returns_commission = SaleReturnDetail::whereMonth('date', date('m', strtotime($req->month)))
        ->whereYear('date', date('Y', strtotime($req->month)))
        ->where('salesManID', $emp->id)->sum('commission');

        $sales = SaleOrder::whereMonth('date', date('m', strtotime($req->month)))
        ->whereYear('date', date('Y', strtotime($req->month)))
        ->where('salesManID', $emp->id)->sum('quantity');

        $returns = SaleReturnDetail::whereMonth('date', date('m', strtotime($req->month)))
        ->whereYear('date', date('Y', strtotime($req->month)))
        ->where('salesManID', $emp->id)->count('returnQuantity');

        $absenties = attendance::whereMonth('date', date('m', strtotime($req->month)))
        ->whereYear('date', date('Y', strtotime($req->month)))
        ->where('status', "Absent")
        ->where('empID', $emp->id)
        ->count();

        $fine = $salaryPerDay * $absenties;

        $advances = advances::where('empID', $emp->id)->get();

        $advance_amount = 0;
        $payments_amount = 0;
        $adv_deduction = 0;
        foreach($advances as $adv){
            $payments = $adv->payments->sum('amount');
            if($payments < $adv->amount){
                $advance_amount += $adv->amount;
                $payments_amount += $payments;
            }
            $adv_deduction = $adv->deduction;
        }

        $salaryPerDay = $emp->salary / $totalDaysInMonth;
        $adv_prv_balance = $advance_amount - $payments_amount;

        $adv_deduction_amount = ($advance_amount * $adv_deduction) / 100;
        if($adv_prv_balance < $adv_deduction_amount)
        {
            $adv_deduction_amount = $adv_prv_balance;
        }
        $adv_cur_balance = $adv_prv_balance - $adv_deduction_amount;

        $netSalary = ($basicSalary + $commission) - ($fine + $returns_commission + $adv_deduction_amount);
        payroll::create(
            [
                'empID' => $emp->id,
                'genDate' => now(),
                'month' => $req->month,
                'salary' => $basicSalary,
                'commission' => $commission,
                'return_commission' => $returns_commission,
                'fine' => $fine,
                'advance' => $advance_amount,
                'adv_payment' => $payments_amount,
                'adv_deduction' => $adv_deduction,
                'adv_deduction_amount' => $adv_deduction_amount,
                'adv_balance' => $adv_cur_balance,
                'netSalary' => $netSalary,
                'workingDays' => $totalDaysInMonth,
                'absenties' => $absenties,
                'fineRate' => $salaryPerDay,
                'sales' => $sales,
                'returns' => $returns,
                'status' => 'Not Paid',
            ]
        );
        $refID = getRef();
        empTransactions::create(
            [
                'empID' => $emp->id,
                'date' => now(),
                'credit' => $basicSalary,
                'refID' => $refID,
                'type' => "Salary",
                'description' => "Salary Generated for the month of ".date('M Y', strtotime($req->month)),
                'createdBy' => auth()->user()->email,
            ]
        );

       }
       if($nos > 0){
        return back()->with('message', 'Payroll Generated for '.$nos." employees for the month of ".date('M Y', strtotime($req->month)));
       }
       else{
        return back()->with('error', 'Already Generated for '.date('M Y', strtotime($req->month)));
       }
    }

    public function payrollGetSalary($id)
    {
        $emp = employees::find($id);
        return $emp->salary;
    }

    public function payrollStore(request $req)
    {
        $ref = getRef();
        $payroll = payroll::create(
            [
                'empID' => $req->emp,
                'accountID' => $req->account,
                'date' => $req->date,
                'notes' => $req->notes,
                'salaryAmount' => $req->salary,
                'createdBy' => auth()->user()->email,
                'refID' => $ref,
            ]
        );

        addTransaction($req->account, $req->date, 'Payroll', 0, $req->salary, $ref, $req->notes);

        return back()->with('message', "Payroll Created Successfully");
    }

    public function payrollDelete($id){
        payroll::where('refID', $id)->delete();
        Transaction::where('refID', $id)->delete();

        return back()->with('error', 'Payroll deleted');
    }

    public function payrollView($id){
        $pay = payroll::find($id);

        return view('hrm.payroll.view', compact('pay'));
    }

    public function print($id){
        $pay = payroll::find($id);

        return view('hrm.payroll.print', compact('pay'));
    }

    public function delete($id){
        $pay = payroll::find($id);
        if($pay->status == 'Paid'){
            return back()->with('error', 'Can Not Be Deleted, Salary Already Paid');
        }

        $pay->delete();
        return back()->with('message', 'Payroll Deleted');
    }

    public function pay(request $req)
    {
        $pay = payroll::find($req->id);
        $refID = getRef();
        if($pay->status == 'Paid')
        {
            return back()->with('error', "Already Paid");
        }
        addTransaction($req->accountID, $req->date, 'Salary', 0, $pay->netSalary, $refID, $req->notes);
        empTransactions::create(
            [
                'empID' => $pay->empID,
                'date' => $req->date,
                'debt' => $pay->netSalary,
                'refID' => $refID,
                'type' => "Salary",
                'description' => $req->notes,
                'createdBy' => auth()->user()->email,
            ]
        );

        $pay->accountID = $req->accountID;
        $pay->issueDate = $req->date;
        $pay->notes = $req->notes;
        $pay->createdBy = auth()->user()->email;
        $pay->refID = $refID;
        $pay->status = "Paid";
        $pay->save();

        return back()->with('message', "Salary Issued");
    }
}
