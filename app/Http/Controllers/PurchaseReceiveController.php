<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseReceive;
use App\Models\Reference;
use App\Models\Stock;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseReceiveController extends Controller
{

    public function receiveProducts($id)
    {
        // Assuming you have a purchase order ID, replace 'your_purchase_id' with the actual ID.
    $purchaseId = $id;

    // Get the unique product IDs for the specified purchase order
    $productIds = PurchaseReceive::where('purchaseID', $purchaseId)
        ->distinct('productID')
        ->pluck('productID');

    // Loop through each product and calculate remaining quantity
    $remainingQuantities = [];
        $data = null;
    foreach ($productIds as $productId) {
        $orderedQty = PurchaseReceive::where('purchaseID', $purchaseId)
            ->where('productID', $productId)
            ->where('orderedQty', '>', 0)
            ->sum('orderedQty');

        $receivedQty = PurchaseReceive::where('purchaseID', $purchaseId)
            ->where('productID', $productId)
            ->where('receivedQty', '>', 0)
            ->sum('receivedQty');

        $remainingQty = $orderedQty - $receivedQty;

        // Store the result in an array or use it as needed
        $remainingQuantities[$productId] = $remainingQty;
        if($remainingQty > 0){
        $product = Product::find($productId);
        $data .= "<tr>";
        $data .= "<td>$product->name</td>";
        $data .= "<td>$remainingQty</td>";
        $data .= "<td> <input type='number' name='qty[]' max='$remainingQty' value='$remainingQty' class='form-control' required min='1' ></td>";
        $data .= "<input type='hidden' name='product[]' value='$productId'>";
        $data .= "</tr>";
        }
    }

    // Now $remainingQuantities contains the remaining quantity for each product ID
    // You can return, display, or further process this data as needed
    return response()->json(
        [
            "data" => $data,
        ]
    );
    }

    public function store(Request $req)
    {
       dd($req);
    }

    public function destroy(Request $request)
    {
        $purchaseReceiveID = $request['purchaseReceiveID'];
        $purchaseID = $request['purchaseID'];

        $purchaseReceive = PurchaseReceive::where('purchaseReceiveID', $purchaseReceiveID)->first();
        $purchaseReceive->delete();
        return redirect()->route('purchase.show', $purchaseID)->with('message', 'Purchase Receive Deleted Successfully!');

    }
}
