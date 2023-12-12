
    <style>
        .widget-table-three .table > tbody > tr > td:first-child {
    border-top-left-radius: 6px;
    padding: 2px;
    border-bottom-left-radius: 6px;
}
    </style>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-card-four">
            <div class="widget-content">
                <div class="w-header">
                    <div class="w-info">
                        <h6 class="value">Sales</h6>
                    </div>
                </div>
                <div class="w-content mt-2">
                    <div class="w-info">
                        <p class="value"> <span>Rs.</span> {{$sale_amount}} </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-card-four">
            <div class="widget-content">
                <div class="w-header">
                    <div class="w-info">
                        <h6 class="value">Purchases</h6>
                    </div>
                </div>
                <div class="w-content mt-2">
                    <div class="w-info">
                        <p class="value"> <span>Rs.</span> {{$purchases_amount}} </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-card-four">
            <div class="widget-content">
                <div class="w-header">
                    <div class="w-info">
                        <h6 class="value">Sale Returns</h6>
                    </div>
                </div>
                <div class="w-content mt-2">
                    <div class="w-info">
                        <p class="value"> <span>Rs.</span> {{$saleReturns_amount}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-card-four">
            <div class="widget-content">
                <div class="w-header">
                    <div class="w-info">
                        <h6 class="value">Purchase Returns</h6>
                    </div>
                </div>
                <div class="w-content mt-2">
                    <div class="w-info">
                        <p class="value"> <span>Rs.</span> {{$purchaseReturns_amount}} </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="chartLine" class="col-xl-9 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Sales</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div id="s-line" class=""></div>
                </div>
            </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-table-three">
            <div class="widget-heading">
                <h5 class="">Top Selling Products</h5>
               {{--  <div class="task-action">
                    <div class="dropdown">
                        <a class="dropdown-toggle" href="#" role="button" id="activitylog" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>

                        <div class="dropdown-menu left" aria-labelledby="activitylog" style="will-change: transform;">
                            <a class="dropdown-item" href="javascript:void(0);">View All</a>
                            <a class="dropdown-item" href="javascript:void(0);">Mark as Read</a>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="widget-content" >
                <div class="table-responsive" style="max-height: 415px; min-height:415px;">
                    <table class="table table-scroll">
                        <thead>
                            <tr>
                                <th colspan="2"><div class="th-content" style="width:90%;">Product</div></th>
                                <th><div class="th-content">Sold</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topSellingProducts as $pro)
                            @if (!$pro->total_sold)
                            @else
                            <tr>
                                <td><div class="td-content product-name"><img src="{{$pro->product->image ?? '/images/6758578.jpg'}}" alt="product"></div></td>
                                <td>{{$pro->product->name}}</td>
                                <td><div class="td-content">{{round($pro->total_sold,1)}}</div></td>
                            </tr>
                            @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-9 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-activity-five">

            <div class="widget-heading">
                <h5 class="">Last 5 Activities</h5>
            </div>

            <div class="widget-content">

                <div class="w-shadow-top"></div>

                <div class="mt-container mx-auto">
                    <div class="simple-tab">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Sales</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Purchases</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Sale Returns</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false">Expenses</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                               <table class="table table-bordered">
                                    <thead>
                                        <th>#</th>
                                        <th>Reference</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody>

                                        @foreach ($lastSales as $sale)
                                        @php
                                            $amount = $sale->saleOrders->sum('subTotal') + $sale->orderTax - $sale->discountValue;
                                            $payments = $sale->salePayments->sum('amount');
                                        @endphp
                                            <tr>
                                                <td>{{ $sale->saleID }}</td>
                                                <td>{{ $sale->referenceNo }}</td>
                                                <td>{{ $sale->account->name }}</td>
                                                <td>{{ $sale->date }}</td>
                                                <td> @if(($amount - $payments) > 0) <div class="badge badge-danger">Due</div> @else <div class="badge badge-success">Paid</div> @endif</td>
                                                <td>{{ $amount  }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                               </table>
                            </div>
                            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>#</th>
                                        <th>Reference</th>
                                        <th>Supplier</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody>

                                        @foreach ($lastPurchases as $purchase)
                                        @php
                                            $amount = $purchase->purchaseOrders->sum('subTotal') + $purchase->orderTax - $purchase->discount;
                                            $payments = $purchase->purchasePayments->sum('amount');
                                        @endphp
                                            <tr>
                                                <td>{{ $purchase->purchaseID }}</td>
                                                <td>{{ $purchase->referenceNo }}</td>
                                                <td>{{ $purchase->account->name }}</td>
                                                <td>{{ $purchase->date }}</td>
                                                <td> @if(($amount - $payments) > 0) <div class="badge badge-danger">Due</div> @else <div class="badge badge-success">Paid</div> @endif</td>
                                                <td>{{ $amount  }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                               </table>
                            </div>
                            <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>#</th>
                                        <th>Sale Invoice #</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody>

                                        @foreach ($lastSaleReturns as $returns)
                                        @php
                                            $amount = $returns->amount;
                                            $payments = $returns->saleReturnPayments->sum('amount');
                                        @endphp
                                            <tr>
                                                <td>{{ $returns->saleReturnID }}</td>
                                                <td>{{ $returns->saleID }}</td>
                                                <td>{{ $returns->customer->name }}</td>
                                                <td>{{ $returns->date }}</td>
                                                <td> @if(($amount - $payments) > 0) <div class="badge badge-danger">Due</div> @else <div class="badge badge-success">Paid</div> @endif</td>
                                                <td>{{ $amount  }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                               </table>
                            </div>
                            <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Account</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($expenses as $exp)
                                            <tr>
                                                <td>{{ $exp->expenseID }}</td>
                                                <td>{{ $exp->date }}</td>
                                                <td>{{ $exp->account->name }}</td>
                                                <td>{{ $exp->category->name }}</td>
                                                <td>{{ $exp->description }}</td>
                                                <td>{{ $exp->amount }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                               </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="w-shadow-bottom"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
        <div class="widget widget-table-one">
            <div class="widget-heading">
                <h5 class="">Recent Transactions</h5>
            </div>

            <div class="widget-content" style="min-height:415px;">
                @php
                    $acct_color = null;
                    $tran_color = null;
                    $amount = 0;
                    $sign = null;
                @endphp
                @foreach ($lastTransactions as $trans)
                @php
                    if($trans->credit > 0)
                    {
                        $tran_color = "rate-inc";
                        $amount = $trans->credit;
                        $sign = "+Rs. ";
                    }
                    if($trans->debt > 0) {
                        $tran_color = "rate-dec";
                        $amount = $trans->debt;
                        $sign = "-Rs. ";
                    }
                    if($trans->debt > 0 && $trans->credit > 0){
                        $tran_color = "rate-inc";
                        $amount = $trans->credit - $trans->debt;
                        $sign = "+Rs. ";
                    }
                    if($trans->account->type == "business")
                    {
                        $acct_color = "t-info";
                    }
                    if($trans->account->type == "customer")
                    {
                        $acct_color = "t-danger";
                    }
                    if($trans->account->type == "supplier")
                    {
                        $acct_color = "t-warning";
                    }

                @endphp
                @if ($amount > 0)
                <div class="transactions-list {{$acct_color}}">
                    <div class="t-item">
                        <div class="t-company-name">
                            <div class="t-icon">
                                <div class="avatar">
                                    <span class="avatar-title text-uppercase">{{getInitials($trans->account->name)}}</span>
                                </div>
                            </div>
                            <div class="t-name">
                                <h4>{{$trans->account->name}}</h4>
                                <p class="meta-date">{{date('d M Y', strtotime($trans->date))}}</p>
                            </div>
                        </div>
                        <div class="t-rate {{$tran_color}}">
                            <p><span>{{$sign}}{{round($amount,0)}}</span></p>
                        </div>
                    </div>
                </div>
                @endif
                @php
                    $amount = 0;
                @endphp
                @endforeach

            </div>
        </div>
    </div>
