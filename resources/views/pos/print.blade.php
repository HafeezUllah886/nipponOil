<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>POS Bill</title>
    <style>
        body{
            background: rgb(232, 232, 232);
            font-size: 15px;
            font-family: "Helvetica";
        }
        .main{
            width: 80mm;
            background: #fff;
            overflow: hidden;
            margin: 0px auto;
            padding: 10px;
        }
        .logo{
            width: 100%;
            overflow: hidden;
            height: 130px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logo img{
            width:80%;
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
    </style>
</head>
<body>
    <div class="main" id="main">
        <div class="logo">
            <img src="{{ asset('images/logo.jpeg') }}" alt="">
        </div>
        <div class="header">
            <p class="text-center"><strong>{{ $sale->warehouse->phone }}</strong></p>
            <p class="text-center"><strong>{{ $sale->warehouse->address }}</strong></p>
            <div class="area-title">
                <p class="text-center bg-dark">Sale Receipt</p>
            </div>
            <table>
                <tr>
                    <td width="15%">Bill # </td>
                    <td width="35%"> {{ $sale->saleID }}</td>
                    <td width="15%">Date: </td>
                    <td width="35%"> {{ date("d-m-Y", strtotime($sale->date)) }}</td>
                    {{-- <td width="15%"> Ref # </td>
                    <td width="55%"> {{ $sale->referenceNo }}</td> --}}
                </tr>
                <tr>
                    <td width="15%"> Customer: </td>
                    <td width="55%" colspan="3"> {{ $sale->account->name }}</td>
                </tr>
            </table>
        </div>
        <div class="content">
            <table>
                <thead class="bg-dark">
                    <th class="text-left">Description</th>
                    <th>Qty</th>
                    <th>Price</th>
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
                   @endphp
                        @if(strlen($detail->product->name) > 25)
                            <tr>
                                <td colspan="4" class="uppercase">{{ $detail->product->name }} -  {{ $detail->product->ltr }} Ltrs</td>
                            </tr>
                            <tr class="bottom-border">
                                <td></td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td>{{ round((($detail->netUnitCost * $detail->quantity) + ($detail->tax - $detail->discountValue)) / $detail->quantity, 2) }}</td>
                                <td class="text-right">{{ round($detail->subTotal,2)}}</td>
                            </tr>
                        @else
                        <tr class="bottom-border">
                            <td class="uppercase">{{ $detail->product->name }} - {{ $detail->product->ltr }} Ltrs</td>
                            <td class="text-center">{{ $detail->quantity }}</td>
                            <td>{{ round((($detail->netUnitCost * $detail->quantity) + ($detail->tax - $detail->discountValue)) / $detail->quantity, 2) }}</td>
                            <td class="text-right">{{ round($detail->subTotal,2)}}</td>
                        </tr>
                        @endif
                   @endforeach
                   <tr>
                    <td colspan="2">
                        Item(s) = {{ $items }} |
                        Total Quantity = {{ $qty }}
                    </td>
                    <td colspan="2" class="text-right" style="font-size: 18px"><strong>{{ number_format($total,0) }}</strong></td>
                   </tr>
                   <tr>
                    <td colspan="2" class="text-right">Tax:</td>
                    <td colspan="2" class="text-right">{{ number_format($sale->orderTax,0) }}</td>
                   </tr>
                   <tr>
                    <td colspan="2" class="text-right">Discount:</td>
                    <td colspan="2" class="text-right">{{ number_format($sale->discountValue,0) }}</td>
                   </tr>
                   <tr>
                    <td colspan="2" class="text-right">Net Amount:</td>
                    <td colspan="2" class="text-right" style="font-size: 20px"><strong>{{ number_format(($total + $sale->orderTax) - $sale->discountValue, 0) }}</strong></td>
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
            <h5 class="text-center">Developed by Diamond Software - 03202565919 <br> </h5>
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
