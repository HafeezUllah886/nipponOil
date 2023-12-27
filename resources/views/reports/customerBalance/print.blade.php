<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report Print</title>
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
                        <svg width="30px" height="30px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" stroke-width="3" stroke="#000000" fill="none"><path d="M39.93,55.72A24.86,24.86,0,1,1,56.86,32.15a37.24,37.24,0,0,1-.73,6"/><path d="M37.86,51.1A47,47,0,0,1,32,56.7"/><path d="M32,7A34.14,34.14,0,0,1,43.57,30a34.07,34.07,0,0,1,.09,4.85"/><path d="M32,7A34.09,34.09,0,0,0,20.31,32.46c0,16.2,7.28,21,11.66,24.24"/><line x1="10.37" y1="19.9" x2="53.75" y2="19.9"/><line x1="32" y1="6.99" x2="32" y2="56.7"/><line x1="11.05" y1="45.48" x2="37.04" y2="45.48"/><line x1="7.14" y1="32.46" x2="56.86" y2="31.85"/><path d="M53.57,57,58,52.56l-8-8,4.55-2.91a.38.38,0,0,0-.12-.7L39.14,37.37a.39.39,0,0,0-.46.46L42,53.41a.39.39,0,0,0,.71.13L45.57,49Z"/></svg>
                        &nbsp; www.Nipponoil.jp
                    </p>
            </div>
           <div class="d-flex justify-content-between">
            <h2 style="text-decoration: underline">CUSTOMERS BALANCE REPORT</h2>
           </div>

        </div>
        <div class="content">
            <table>
                <thead class="bg-dark">
                    <th>#</th>
                    <th>Account #</th>
                    <th class="text-left">Account Title</th>
                    <th class="text-left">Contact #</th>
                    <th class="text-left">Area</th>
                    <th class="text-left">Address</th>
                    <th class="text-right">Balance</th>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($accounts as $key => $account)
                    @php
                        $total += $account->balance;
                    @endphp
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td class="text-center">{{ $account->accountNumber }}</td>
                            <td>{{ $account->name }}</td>
                            <td>{{ $account->phone }}</td>
                            <td>{{ $account->area }}</td>
                            <td>{{ $account->address }}</td>
                            <td class="text-right">{{ $account->balance }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <th colspan="6" class="text-right">Total Balance</th>
                    <th class="text-right">{{ $total }}</th>
                </tfoot>
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

   setTimeout(function() {
        window.print();
    }, 2000);

        setTimeout(function() {
            window.open('{{ url("/reports/customerBalance") }}', "_self");
    }, 5000);

</script>
