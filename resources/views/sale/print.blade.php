<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>POS Bill</title>
    <style>
        body{
            background: #fff;
            font-size: 15px;
            font-family: "Helvetica";
        }
        .main{
            width: 95%;
            background: #fff;
            overflow: hidden;
            margin: 0px auto;
            padding: 10px;
        }

        .logo{
            width: 100%;
            overflow: hidden;
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logo img{
            width:60%;
            height: 140px;
        }
        .header p{
            margin: 2px 0px;
        }
        .content{
            overflow: hidden;
            width: 100%;
        }
        .content table{
            width: 100%;
            border-collapse: collapse;
        }
        .bg-dark{
            background: black;
            color:#ffff;
        }
        .text-left{
            text-align: left !important;
        }
        .text-right{
            text-align: right !important;
        }
        .text-center{
            text-align: center !important;
        }
        .area-title{
            font-size: 18px;
        }
        tr.bottom-border {
            border-bottom: 1px solid #ccc; /* Add a 1px solid border at the bottom of rows with the "my-class" class */
        }
        .uppercase{
            text-transform: uppercase;
        }
        .d-flex
        {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .office
        {
            border-top:2px dashed black;
        }
    </style>
</head>
<body>
    <div class="main" id="main">
        <div class="logo">
            <img src="{{ asset('images/logo.jpeg') }}" alt="">
        </div>
        <div class="header">
          {{--   <p class="text-center"><strong>{{ $sale->warehouse->phone }}</strong></p>
            <p class="text-center"><strong>{{ $sale->warehouse->address }}</strong></p>
            <p class="text-center"><strong>{{ $sale->warehouse->email }}</strong></p> --}}
            <div class="area-title">
                <p class="text-center bg-dark">
                    Nippon Oil Official Branch, Opps Nadra Office, Maqbool Tyre Market, Double Road, Quetta
                </p>
                <p class="text-center d-flex">
                    <?xml version="1.0" encoding="utf-8"?>
                    <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                    <svg fill="#000000" width="30px" height="30px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <title>whatsapp</title>
                    <path d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732 0.737 5.291 2.022 7.491l-0.038-0.070-2.109 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h0.006c8.209-0.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507l0 0zM16.062 28.228h-0.005c-0 0-0.001 0-0.001 0-2.319 0-4.489-0.64-6.342-1.753l0.056 0.031-0.451-0.267-4.675 1.227 1.247-4.559-0.294-0.467c-1.185-1.862-1.889-4.131-1.889-6.565 0-6.822 5.531-12.353 12.353-12.353s12.353 5.531 12.353 12.353c0 6.822-5.53 12.353-12.353 12.353h-0zM22.838 18.977c-0.371-0.186-2.197-1.083-2.537-1.208-0.341-0.124-0.589-0.185-0.837 0.187-0.246 0.371-0.958 1.207-1.175 1.455-0.216 0.249-0.434 0.279-0.805 0.094-1.15-0.466-2.138-1.087-2.997-1.852l0.010 0.009c-0.799-0.74-1.484-1.587-2.037-2.521l-0.028-0.052c-0.216-0.371-0.023-0.572 0.162-0.757 0.167-0.166 0.372-0.434 0.557-0.65 0.146-0.179 0.271-0.384 0.366-0.604l0.006-0.017c0.043-0.087 0.068-0.188 0.068-0.296 0-0.131-0.037-0.253-0.101-0.357l0.002 0.003c-0.094-0.186-0.836-2.014-1.145-2.758-0.302-0.724-0.609-0.625-0.836-0.637-0.216-0.010-0.464-0.012-0.712-0.012-0.395 0.010-0.746 0.188-0.988 0.463l-0.001 0.002c-0.802 0.761-1.3 1.834-1.3 3.023 0 0.026 0 0.053 0.001 0.079l-0-0.004c0.131 1.467 0.681 2.784 1.527 3.857l-0.012-0.015c1.604 2.379 3.742 4.282 6.251 5.564l0.094 0.043c0.548 0.248 1.25 0.513 1.968 0.74l0.149 0.041c0.442 0.14 0.951 0.221 1.479 0.221 0.303 0 0.601-0.027 0.889-0.078l-0.031 0.004c1.069-0.223 1.956-0.868 2.497-1.749l0.009-0.017c0.165-0.366 0.261-0.793 0.261-1.242 0-0.185-0.016-0.366-0.047-0.542l0.003 0.019c-0.092-0.155-0.34-0.247-0.712-0.434z"></path>
                    </svg>
                     &nbsp; 0311-0000271 &nbsp;
                     <?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                     <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <path d="M14.05 6C15.0268 6.19057 15.9244 6.66826 16.6281 7.37194C17.3318 8.07561 17.8095 8.97326 18 9.95M14.05 2C16.0793 2.22544 17.9716 3.13417 19.4163 4.57701C20.8609 6.01984 21.7721 7.91101 22 9.94M18.5 21C9.93959 21 3 14.0604 3 5.5C3 5.11378 3.01413 4.73086 3.04189 4.35173C3.07375 3.91662 3.08968 3.69907 3.2037 3.50103C3.29814 3.33701 3.4655 3.18146 3.63598 3.09925C3.84181 3 4.08188 3 4.56201 3H7.37932C7.78308 3 7.98496 3 8.15802 3.06645C8.31089 3.12515 8.44701 3.22049 8.55442 3.3441C8.67601 3.48403 8.745 3.67376 8.88299 4.05321L10.0491 7.26005C10.2096 7.70153 10.2899 7.92227 10.2763 8.1317C10.2643 8.31637 10.2012 8.49408 10.0942 8.64506C9.97286 8.81628 9.77145 8.93713 9.36863 9.17882L8 10C9.2019 12.6489 11.3501 14.7999 14 16L14.8212 14.6314C15.0629 14.2285 15.1837 14.0271 15.3549 13.9058C15.5059 13.7988 15.6836 13.7357 15.8683 13.7237C16.0777 13.7101 16.2985 13.7904 16.74 13.9509L19.9468 15.117C20.3262 15.255 20.516 15.324 20.6559 15.4456C20.7795 15.553 20.8749 15.6891 20.9335 15.842C21 16.015 21 16.2169 21 16.6207V19.438C21 19.9181 21 20.1582 20.9007 20.364C20.8185 20.5345 20.663 20.7019 20.499 20.7963C20.3009 20.9103 20.0834 20.9262 19.6483 20.9581C19.2691 20.9859 18.8862 21 18.5 21Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                     </svg>
                     081-2442221
                     &nbsp;
                     <?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                        <svg width="30px" height="30px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" stroke-width="3" stroke="#000000" fill="none"><path d="M39.93,55.72A24.86,24.86,0,1,1,56.86,32.15a37.24,37.24,0,0,1-.73,6"/><path d="M37.86,51.1A47,47,0,0,1,32,56.7"/><path d="M32,7A34.14,34.14,0,0,1,43.57,30a34.07,34.07,0,0,1,.09,4.85"/><path d="M32,7A34.09,34.09,0,0,0,20.31,32.46c0,16.2,7.28,21,11.66,24.24"/><line x1="10.37" y1="19.9" x2="53.75" y2="19.9"/><line x1="32" y1="6.99" x2="32" y2="56.7"/><line x1="11.05" y1="45.48" x2="37.04" y2="45.48"/><line x1="7.14" y1="32.46" x2="56.86" y2="31.85"/><path d="M53.57,57,58,52.56l-8-8,4.55-2.91a.38.38,0,0,0-.12-.7L39.14,37.37a.39.39,0,0,0-.46.46L42,53.41a.39.39,0,0,0,.71.13L45.57,49Z"/></svg>
                        &nbsp; www.Nipponoil.jp
                    </p>
                    <p class="text-center"> <strong>{{ auth()->user()->warehouse->name }}</strong></p>
            </div>
            <table width="100%">
                <tr>
                    <td width="">Customer Name: </td>
                    <td width=""> {{ $sale->account->name }}</td>
                    <td width="40%"></td>
                    <td width="">Invoice No. </td>
                    <td class="text-right">{{ $sale->saleID }} </td>
                </tr>
                <tr>
                    <td width=""> Contact: </td>
                    <td width=""> {{ $sale->account->phone }}</td>
                    <td></td>
                    <td width=""> Date: </td>
                    <td class="text-right"> {{ date("d-m-Y", strtotime($sale->date)) }}</td>
                </tr>
                <tr>
                    <td width=""> Address: </td>
                    <td width="" colspan="2"> {{ $sale->account->address }}</td>
                    <td width=""> Sales Man: </td>
                    <td class="text-right"> {{ $sale->salesMan->name }}</td>
                </tr>
            </table>
        </div>
        <div class="content">
            <table>
                <thead class="bg-dark">
                    <th class="text-left">Code</th>
                    <th class="text-left">Description</th>
                    <th>Ltrs</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Amount</th>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                        $items = 0;
                        $qty = 0;
                    @endphp
                   @foreach ($sale->saleOrders as $detail)
                   @php
                       $total += $detail->subTotal;
                       $items += 1;
                       $qty += $detail->quantity;
                       $discount = $detail->discountValue / $detail->quantity;
                   @endphp
                        <tr class="bottom-border">
                            <td>{{ $detail->product->code }}</td>
                            <td class="uppercase">{{ $detail->product->name }}</td>
                            <td class="text-center">{{ $detail->product->ltr }}</td>
                            <td class="text-center">{{ $detail->quantity }}</td>
                            <td class="text-center">{{ $detail->netUnitCost}}</td>
                            <td class="text-center">{{ $discount }}</td>
                            <td class="text-right">{{ round($detail->subTotal,2)}}</td>
                        </tr>
                   @endforeach
                   <tr>
                    <td colspan="4">
                        Item(s) = {{ $items }} |
                        Total Quantity = {{ $qty }}
                    </td>
                    <td colspan="5" class="text-right" style="font-size: 18px"><strong>{{ number_format($total,2) }}</strong></td>
                   </tr>
                   <tr>
                    <td colspan="5" class="text-right">Tax:</td>
                    <td colspan="4" class="text-right">{{ number_format($sale->orderTax,2) }}</td>
                   </tr>
                   <tr>
                    <td colspan="5" class="text-right">Discount:</td>
                    <td colspan="5" class="text-right">{{ number_format($sale->discountValue,2) }}</td>
                   </tr>
                   <tr>
                    <td colspan="5" class="text-right">Net Amount:</td>
                    @php
                        $net = ($total + $sale->orderTax) - $sale->discountValue;
                    @endphp
                    <td colspan="5" class="text-right" style="font-size: 20px"><strong>{{ number_format($net, 2) }}</strong></td>
                    </tr>
                    @php
                        $bill_balance = $net - $sale->salePayments->sum('amount');
                        $account_balance = getAccountBalance($sale->account->accountID);
                    @endphp
                    <tr>
                        <td colspan="5" class="text-right">Previous Balance:</td>
                        <td colspan="5" class="text-right" style="font-size: 20px"><strong>{{ number_format($account_balance - $bill_balance, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right">Bill Balance:</td>
                        <td colspan="5" class="text-right" style="font-size: 20px"><strong>{{ number_format($bill_balance, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right">Account Balance:</td>
                        <td colspan="5" class="text-right" style="font-size: 20px"><strong>{{ number_format(getAccountBalance($sale->account->accountID), 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="notes">
            <h5>Notes:</h5>
            <span>{{ $sale->points }}</span>
        </div>
        <div class="footer">
            <hr>
            <h5 class="text-center">Developed by Diamond Software {{-- - 03202565919  --}}<br> </h5>
        </div>
        <div class="office">
            <div class="area-title">
                <p class="text-center bg-dark">
                    For Office Record Only
                </p>
            </div>
            <table width="100%">
                <tr>
                    <td width="">Customer Name: </td>
                    <td width=""> {{ $sale->account->name }}</td>
                    <td width="30%"></td>
                    <td width="">Invoice No. </td>
                    <td class="text-right">{{ $sale->saleID }} </td>
                </tr>
                <tr>
                    <td width=""> Contact: </td>
                    <td width=""> {{ $sale->account->phone }}</td>
                    <td></td>
                    <td width=""> Date: </td>
                    <td class="text-right"> {{ date("d-m-Y", strtotime($sale->date)) }}</td>
                </tr>
                <tr>
                    <td width=""> Address: </td>
                    <td width="" colspan="2"> {{ $sale->account->address }}</td>
                    <td width=""> Sales Man: </td>
                    <td class="text-right"> {{ $sale->salesMan->name }}</td>
                </tr>
                <tr>
                    <td width=""> Items: </td>
                    <td width=""> {{ $items }}</td>
                    <td width=""></td>
                    <td width=""> Total Bill: </td>
                    <td class="text-right"> {{ number_format($net, 2) }}</td>
                </tr>
                <tr>
                    <td width=""> Quantity: </td>
                    <td width=""> {{ $qty }}</td>
                    <td width=""></td>
                    <td width=""> Paid: </td>
                    @php
                        $paid = $sale->salePayments->sum('amount')
                    @endphp
                    <td class="text-right"> {{ number_format($paid, 2) }}</td>
                </tr>
                <tr>
                    <td width=""></td>
                    <td width=""></td>
                    <td width=""></td>
                    <td width=""> Balance </td>
                    <td class="text-right"> {{ number_format($net - $paid, 2) }}</td>
                </tr>
                <tr>
                    <td width=""></td>
                    <td width=""></td>
                    <td width=""></td>
                    <td width=""></td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td width="">Customer</td>
                    <td width="">______________</td>
                    <td width=""></td>
                    <td width="">MD</td>
                    <td class="text-right">_____________</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
<script src="{{ asset('src/plugins/src/jquery-ui/jquery-ui.min.js') }}"></script>
<script>

   setTimeout(function() {
        window.print();
    }, 2000);

        setTimeout(function() {
            window.open('{{ url("/sale/1/1/0") }}', "_self");
    }, 5000);

</script>
