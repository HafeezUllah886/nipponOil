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
                                                    <div class="d-flex">
                                                        <img class="company-logo" style="width:250px;" src="{{asset('images/logo.jpeg')}}" alt="company">
                                                        {{-- <h3 class="in-heading align-self-center">Bolan Surgico, Quetta</h3> --}}
                                                    </div>
                                                    {{-- <p class="inv-street-addr mt-3">XYZ Delta Street</p>
                                                    <p class="inv-email-address">info@company.com</p>
                                                    <p class="inv-email-address">(120) 456 789</p> --}}
                                                </div>

                                                <div class="col-sm-6 text-sm-end">
                                                    <p class="inv-list-number mt-sm-3 pb-sm-2 mt-4"><span class="inv-title">Quotation : </span> <span class="inv-number">#{{$quotation->id}}</span></p>
                                                    <p class="inv-created-date mt-0"><span class="inv-title">Date : </span> <span class="inv-date">{{date("d M Y", strtotime($quotation->date))}}</span></p>
                                                   {{--  <p class="inv-due-date"><span class="inv-title">Due Date : </span> <span class="inv-date">26 Mar 2022</span></p> --}}
                                                </div>
                                            </div>

                                        </div>

                                        <div class="inv--detail-section inv--customer-detail-section">

                                            <div class="row">

                                                <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4 align-self-center">
                                                    <p class="inv-to">Quotation To</p>
                                                </div>

                                                {{-- <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8 align-self-center order-sm-0 order-1 text-sm-end">
                                                    <h6 class="inv-to">Quotation From</h6>
                                                </div> --}}

                                                <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4">
                                                    <p class="inv-customer-name">{{$quotation->customer}}</p>
                                                    <p class="inv-street-addr">{{$quotation->address}}</p>
                                                    <p class="inv-email-address">{{$quotation->phone}}</p>
                                                </div>

                                                {{-- <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8 col-12 order-sm-0 order-1 text-sm-end">
                                                    <p class="inv-customer-name">Bolan Surgico</p>
                                                    <p class="inv-street-addr">Shop # 123 Abc Road, Quetta</p>
                                                    <p class="inv-email-address">081-1234567</p>
                                                </div> --}}

                                            </div>

                                        </div>

                                        <div class="inv--product-table-section">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead class="">
                                                        <tr>
                                                            <th scope="col">S.No</th>
                                                            <th scope="col">Items</th>
                                                            <th class="text-end" scope="col">Qty</th>
                                                            <th class="text-end" scope="col">Price</th>
                                                            <th class="text-end" scope="col">Discount</th>
                                                            <th class="text-end" scope="col">Tax</th>
                                                            <th class="text-end" scope="col">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $total = 0;
                                                            $ser = 0;
                                                        @endphp
                                                        @foreach ($quotation->detail as $product)
                                                            @php
                                                                $total += $product->net;
                                                                $ser += 1;
                                                            @endphp
                                                             <tr>
                                                                <td>{{$ser}}</td>
                                                                <td>{{$product->product->name}}</td>
                                                                <td class="text-end">{{$product->qty}}</td>
                                                                <td class="text-end">{{$product->price}}</td>
                                                                <td class="text-end">{{$product->discount}}</td>
                                                                <td class="text-end">{{$product->tax}}</td>
                                                                <td class="text-end">{{$product->net}}</td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="inv--total-amounts">

                                            <div class="row mt-4">
                                                <div class="col-sm-5 col-12 order-sm-0 order-1">
                                                </div>
                                                <div class="col-sm-7 col-12 order-sm-1 order-0">
                                                    <div class="text-sm-end">
                                                        <div class="row">
                                                            <div class="col-sm-8 col-7">
                                                                <p class="">Sub Total :</p>
                                                            </div>
                                                            <div class="col-sm-4 col-5">
                                                                <p class="">Rs. {{$total}}</p>
                                                            </div>
                                                            <div class="col-sm-8 col-7">
                                                                <p class="">Tax:</p>
                                                            </div>
                                                            <div class="col-sm-4 col-5">
                                                                <p class="">Rs. {{$quotation->tax}}</p>
                                                            </div>
                                                            <div class="col-sm-8 col-7">
                                                                <p class=" discount-rate">Shipping :</p>
                                                            </div>
                                                            <div class="col-sm-4 col-5">
                                                                <p class="">Rs. {{$quotation->shipping}}</p>
                                                            </div>
                                                            <div class="col-sm-8 col-7">
                                                                <p class=" discount-rate">Discount:</p>
                                                            </div>
                                                            <div class="col-sm-4 col-5">
                                                                <p class="">Rs. {{$quotation->discount}}</p>
                                                            </div>
                                                            <div class="col-sm-8 col-7 grand-total-title">
                                                                <p class="discount-rate">Grand Total:</p>
                                                            </div>
                                                            <div class="col-sm-4 col-5">
                                                                <p class="">Rs. {{$total + $quotation->tax + $quotation->shipping - $quotation->discount}}</p>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="inv--note">

                                            <div class="row mt-4">
                                                <div class="col-sm-12 col-12 order-sm-0 order-1">
                                                    <p>Notes: {{$quotation->notes ?? "Thank you for doing Business with us."}}</p>
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
                                    <a href="{{url('/quotation')}}" class="btn btn-dark btn-edit">Back</a>
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
