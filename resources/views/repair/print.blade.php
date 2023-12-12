<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Repair Work Print</title>
    <style>
        body{
            background: rgb(232, 232, 232);
            font-size: 15px;
            font-family: "Helvetica";
        }
        .main{
            max-width: 400px;
            background: #fff;
            overflow: hidden;
            margin: 0px auto;
            padding: 5px;
        }
        .logo{
            width: 100%;
            overflow: hidden;
            height: 120px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logo img{
            width:40%;
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
            <img src="{{ asset(auth()->user()->warehouse->logo) }}" alt="">
        </div>
        <div class="header">
            <p class="text-center"><strong>{{ $repair->warehouse->phone }}</strong></p>
            <p class="text-center"><strong>{{ $repair->warehouse->address }}</strong></p>
            <p class="text-center"><strong>{{ $repair->warehouse->email }}</strong></p>
            <div class="area-title">
                <p class="text-center bg-dark">Repair Work Receipt</p>
            </div>
            <table>
                <tr>
                    <td width="15%">ID # </td>
                    <td width="35%"> {{ $repair->id }}</td>
                </tr>
                <tr>
                    <td width="15%">Customer: </td>
                    <td width="35%"> {{ $repair->customerName }}</td>
                </tr>
                <tr>
                    <td width="15%">Contact: </td>
                    <td width="35%"> {{ $repair->contact }}</td>
                </tr>
                <tr>
                    <td width="15%">Product: </td>
                    <td width="35%"> {{ $repair->product }}</td>
                </tr>
                <tr>
                    <td width="15%">Accessories: </td>
                    <td width="35%"> {{ $repair->accessories }}</td>
                </tr>
                <tr>
                    <td width="15%">Fault: </td>
                    <td width="35%"> {{ $repair->fault }}</td>
                </tr>
                <tr>
                    <td width="15%">Date: </td>
                    <td width="35%"> {{ date("d-m-Y", strtotime($repair->date)) }}</td>
                </tr>
                <tr>
                    <td width="15%">Return Date: </td>
                    <td width="35%"> {{ date("d-m-Y", strtotime($repair->returnDate)) }}</td>
                </tr>
                <tr>
                    <td width="15%"> Charges: </td>
                    <td width="55%" colspan="3"> {{ $repair->charges }}</td>
                </tr>
                <tr>
                    <td width="15%"> Payment: </td>
                    <td width="55%" colspan="3"> {{ $repair->payment->sum('amount') }}</td>
                </tr>
                <tr>
                    <td width="15%"> Remaining: </td>
                    <td width="55%" colspan="3"> {{ $repair->charges - $repair->payment->sum('amount') }}</td>
                </tr>
            </table>
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
    window.print();

        setTimeout(function() {
            window.open('{{ url("/repair") }}', "_self");
    }, 5000);

</script>
