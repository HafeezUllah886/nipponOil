<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Sale;
use App\Models\SaleOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class customerController extends Controller
{
    public function purchaseHistory($id){
        $sales = Sale::where('customerID', $id)->get();
        $ids = [];
        foreach($sales as $sale)
        {
            $ids[] = $sale->saleID;
        }

        $productStats = SaleOrder::whereIn('saleID', $ids)
        ->select('productID', DB::raw('avg(netUnitCost) as average_price'), DB::raw('sum(quantity) as total_quantity'))
        ->groupBy('productID')
        ->with('product')
        ->get();

        $customer = Account::find($id);
        return view('account.customerPurchaseHistory', compact('customer', 'productStats'));
    }

}
