@extends('layouts.admin')
@section('title', 'Purchase Show')
@section('content')
<div class="row invoice layout-top-spacing layout-spacing">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

        <div class="doc-container">

            <div class="row">

                <div class="col-md-9">

                    <div class="invoice-container">
                        <div class="invoice-inbox">

                            <div id="ct" class="">

                                <div class="invoice-00001">
                                    <div class="content-section">

                                        <div class="inv--head-section inv--detail-section">

                                            <div class="row">

                                                <div class="col-sm-6 col-12 mr-auto">
                                                        <img class="company-logo" style="width:300px;" src="{{asset('images/logo.jpeg')}}" alt="company">
                                                </div>

                                                <div class="col-sm-6 text-sm-end">
                                                    <p class="inv-list-number mt-sm-3 pb-sm-2 mt-4"><span class="inv-title">Order : </span> <span class="inv-number">#{{ $purchase->purchaseID }}</span></p>
                                                    <p class="inv-created-date mt-0"><span class="inv-title">Date : </span> <span class="inv-date">{{ date('d M Y', strtotime($purchase->date)) }}</span></p>
                                                   {{--  <p class="inv-due-date"><span class="inv-title">Due Date : </span> <span class="inv-date">26 Mar 2022</span></p> --}}
                                                </div>
                                            </div>

                                        </div>

                                        <div class="inv--detail-section inv--customer-detail-section">

                                            <div class="row">

                                                <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4 align-self-center">
                                                    <p class="inv-to">Order To</p>
                                                </div>

                                                <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8 align-self-center order-sm-0 order-1 text-sm-end">
                                                    <h6 class="inv-to">Order From</h6>
                                                </div>

                                                <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4">
                                                    <p class="inv-customer-name">{{ $purchase->account->name }}</p>
                                                    <p class="inv-street-addr">{{ $purchase->account->phone }}</p>
                                                    <p class="inv-email-address">{{ $purchase->account->address }}</p>
                                                </div>

                                                <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8 col-12 order-sm-0 order-1 text-sm-end">
                                                    <p class="inv-customer-name">Nippon Oil Quetta</p>
                                                    <p class="inv-street-addr">Opps Nadra Office, Maqbool Tyre Market, Double Road, Quetta</p>
                                                    <p class="inv-email-address">0311-0000271</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="inv--product-table-section">
                                            <div class="table-">
                                                <table class="table">
                                                    <thead class="">
                                                        <tr>

                                                            <th scope="col">Items</th>
                                                            <th class="text-end" scope="col">Viscosity</th>
                                                            <th class="text-end" scope="col">Qty</th>
                                                            <th class="text-end" scope="col">Ltr</th>
                                                            <th class="text-end" scope="col">Weight</th>
                                                            <th class="text-end" scope="col">Total Ltrs</th>
                                                            <th class="text-end" scope="col">Total Weight</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $totalLtrs = 0;
                                                            $totalWeight = 0;
                                                         @endphp
                                                        @foreach ($purchase->purchaseOrders as $key => $products)
                                                            <tr>
                                                                @php
                                                                    $totalLtrs += $products->product->ltr * $products->quantity;
                                                                    $totalWeight += $products->product->weight * $products->quantity;
                                                                @endphp
                                                                <td>{{ $products->product->name }}</td>
                                                                <td>{{ $products->product->grade }}</td>
                                                                <td class="text-center">{{ $products->quantity }}</td>
                                                                <td class="text-end">{{ $products->product->ltr }} Ltrs</td>
                                                                <td class="text-end">{{ $products->product->weight }} KGs</td>
                                                                <td class="text-end">{{ $products->product->ltr * $products->quantity}} Ltrs</td>
                                                                <td class="text-end">{{ $products->product->weight * $products->quantity}} KGs</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <th class="text-end" colspan="5">Total</th>
                                                        <th class="text-end">{{ $totalLtrs }} Ltrs</th>
                                                        <th class="text-end">{{ $totalWeight }} KGs</th>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="inv--note">

                                            <div class="row mt-4">
                                                <div class="col-sm-12 col-12 order-sm-0 order-1">
                                                    <p>Notes: {{$purchase->description}}</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="invoice-actions-btn">

                        <div class="invoice-action-btn">

                            <div class="row">

                                <div class="col-xl-12 col-md-3 col-sm-6">
                                    <a href="javascript:void(0);" class="btn btn-secondary btn-print  action-print">Print</a>
                                </div>

                                <div class="col-xl-12 col-md-3 col-sm-6">
                                    <a href="{{url('/purchase')}}" class="btn btn-dark btn-edit">Back</a>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection
@section('more-css')
    <link rel="stylesheet" href="{{asset('src/assets/css/light/apps/invoice-preview.css')}}">
    <link rel="stylesheet" href="{{asset('src/assets/css/dark/apps/invoice-preview.css')}}">
@endsection

@section('more-script')
    <script src="{{asset('src/assets/js/apps/invoice-preview.js')}}"></script>
@endsection
