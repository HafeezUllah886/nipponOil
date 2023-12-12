<div class="col">
    <div class="card bg-white">
        <div class="card-header">
            <h5 class="card-title">Purchase</h5>
        </div>
        <div class="card-body">
            <table class="w-100">
                <tr>
                    <td style="font-weight:900;">Purchases</td>
                    <td style="font-weight:900;text-align:right;">{{$purchases->count()}}</td>
                </tr>
                <tr>
                    <td style="font-weight:900;">Amount</td>
                    <td style="font-weight:900;text-align:right;">{{$purchases_amount}}</td>
                </tr>
                <tr>
                    <td style="font-weight:900;">Paid</td>
                    <td style="font-weight:900;text-align:right;">{{$purchasePaid}}</td>
                </tr>

            </table>
        </div>
    </div>
</div>
<div class="col">
    <div class="card bg-white">
        <div class="card-header">
            <h5 class="card-title">Sale</h5>
        </div>
        <div class="card-body">
            <table class="w-100">
                <tr>
                    <td style="font-weight:900;">Sales</td>
                    <td style="font-weight:900;text-align:right;">{{$sales->count()}}</td>
                </tr>
                <tr>
                    <td style="font-weight:900;">Amount</td>
                    <td style="font-weight:900;text-align:right;">{{$sale_amount}}</td>
                </tr>
                <tr>
                    <td style="font-weight:900;">Paid</td>
                    <td style="font-weight:900;text-align:right;">{{$salePaid}}</td>
                </tr>

            </table>
        </div>
    </div>
</div>
<div class="col">
    <div class="card bg-white">
        <div class="card-header">
            <h5 class="card-title"> <div class="col">Purchase Returns</div></h5>
        </div>
        <div class="card-body">
            <table class="w-100">
                <tr>
                    <td style="font-weight:900;">Returns</td>
                    <td style="font-weight:900;text-align:right;">{{$purchaseReturns->count()}}</td>
                </tr>
                <tr>
                    <td style="font-weight:900;">Amount</td>
                    <td style="font-weight:900;text-align:right;">{{$purchaseReturns_amount}}</td>
                </tr>
                <tr>
                    <td style="font-weight:900;">Paid</td>
                    <td style="font-weight:900;text-align:right;">{{$purchaseReturnPaid}}</td>
                </tr>

            </table>
        </div>
    </div>
</div>
<div class="col">
    <div class="card bg-white">
        <div class="card-header">
            <h5 class="card-title"> <div class="col">Sale Returns</div></h5>
        </div>
        <div class="card-body">
            <table class="w-100">
                <tr>
                    <td style="font-weight:900;">Returns</td>
                    <td style="font-weight:900;text-align:right;">{{$saleReturns->count()}}</td>
                </tr>
                <tr>
                    <td style="font-weight:900;">Amount</td>
                    <td style="font-weight:900;text-align:right;">{{$saleReturns_amount}}</td>
                </tr>
                <tr>
                    <td style="font-weight:900;">Paid</td>
                    <td style="font-weight:900;text-align:right;">{{$saleReturnPaid}}</td>
                </tr>

            </table>
        </div>
    </div>
</div>
<div class="col">
    <div class="card bg-white">
        <div class="card-header">
            <h5 class="card-title">Profit / Loss</h5>
        </div>
        <div class="card-body">
            <table class="w-100">
                <tr>
                    <td style="font-weight:900;">Sale</td>
                    <td style="font-weight:900;text-align:right;">+ {{ $sale_amount }}</td>
                </tr>
                <tr>
                    <td style="font-weight:900;">Purchase</td>
                    <td style="font-weight:900;text-align:right;">- {{ $purchases_amount }}</td>
                </tr>
                <tr>
                    <td style="font-weight:900;">Sale Return</td>
                    <td style="font-weight:900;text-align:right;">- {{$saleReturns_amount}}</td>
                </tr>
                <tr>
                    <td style="font-weight:900;">Purchase Return</td>
                    <td style="font-weight:900;text-align:right;">+ {{$purchaseReturns_amount}}</td>
                </tr>
                <tr>
                    <td style="font-weight:900;">Expenses</td>
                    <td style="font-weight:900;text-align:right;">- {{$expenses->sum('amount')}}</td>
                </tr>
                <tr>
                    <td style="font-weight:900;">Profit/Loss</td>
                    <td style="font-weight:900;text-align:right;">{{ ($sale_amount + $purchaseReturns_amount) - ($purchases_amount + $saleReturns_amount + $expenses->sum('amount'))}}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
