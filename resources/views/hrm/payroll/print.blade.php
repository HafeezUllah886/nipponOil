<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pay Slip</title>
    <style>
        body{
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }

        .header{
            text-align: center;
            line-height: 1px;
        }

        table, th, td {
            border-collapse: collapse;
        }
        .salaryTable thead{
            background: rgb(197, 196, 196);
        }
        .salaryTable thead th{
            border: 1px solid black;
        }

        .salaryTable tbody{
            border: 1px solid black;

        }
        .salaryTable tbody tr td{
            border-left: 1px solid black;
            padding: 10px;
        }

        .heading{
           /*  background: rgb(142, 142, 142); */
            color: black;
            padding: 5px;
           /*  text-align: center; */
        }

        .text-center{
            text-align: center;
        }
        .text-right{
            text-align: right
        }
        .footer{
            margin-top: 20px;
        }
        .strong{
            font-weight: 700;
        }
        .footer table tr{
            line-height: 30px;
        }
    </style>
</head>
<body>
<div class="header">
    <h3>Salary Slip</h3>
    <h4>Bolan Surgico</h4>
    <h5>Salary Month - {{ date('F Y', strtotime($pay->month)) }}</h5>
</div>
<div class="empDetails">
    <h4 class="heading">Personal Details</h4>
    <table style="width:100%;">
        <tr>
            <td style="width:25%;">Employee ID</td>
            <td style="width:25%;">: {{ $pay->emp->id }}</td>
            <td style="width:25%;">Employee Name</td>
            <td style="width:25%;">: {{ ucfirst($pay->emp->name) }}</td>
        </tr>
        <tr>
            <td style="width:25%;">Phone Number</td>
            <td style="width:25%;">: {{ $pay->emp->phone }}</td>
            <td style="width:25%;">Email Address</td>
            <td style="width:25%;">: {{ $pay->emp->email }}</td>
        </tr>
        <tr>
            <td style="width:25%;">Designation</td>
            <td style="width:25%;">: {{ $pay->emp->designation }}</td>
            <td style="width:25%;">Date of Enrollment</td>
            <td style="width:25%;">: {{ date('d M Y', strtotime($pay->emp->doe)) }}</td>
        </tr>
        <tr>
            <td>Address</td>
            <td colspan="3">: {{ $pay->emp->address }}</td>
        </tr>

    </table>
    <div class="payDetails">
        <h4 class="heading">Salary Details</h4>
        <table style="width:100%;" class="salaryTable">
            <thead>
                <th>Earning</th>
                <th>Amount</th>
                <th>Deduction</th>
                <th>Amount</th>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Pay</td>
                    <td class="text-center">{{ $pay->salary }}</td>
                    <td>Fine</td>
                    <td class="text-center">{{ $pay->fine }}</td>
                </tr>
                <tr>
                    <td>Sales Commission</td>
                    <td class="text-center">{{ $pay->commission }}</td>
                    <td>Sales Return Commission</td>
                    <td class="text-center">{{ $pay->return_commission }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-center"></td>
                    <td>Advance</td>
                    <td class="text-center">{{ $pay->adv_deduction_amount }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-center"></td>
                    <td></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td class="text-right">Total Earning</td>
                    <td class="text-center">{{ $pay->commission + $pay->salary }}</td>
                    <td class="text-right">Total Deductions</td>
                    <td class="text-center">{{ $pay->return_commission + $pay->fine + $pay->adv_deduction_amount}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @if($pay->adv_deduction_amount > 0)
    <div class="payDetails">
        <h4 class="heading">Advance Salary Details</h4>
        <table style="width:100%;" class="salaryTable">
            <thead>
                <th>Advance Amount</th>
                <th>Already Paid</th>
                <th>Deduction Rate</th>
                <th>Deduction Amount</th>
                <th>Balance</th>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">{{ $pay->advance }}</td>
                    <td class="text-center">{{ $pay->adv_payment }}</td>
                    <td class="text-center">{{ $pay->adv_deduction }}%</td>
                    <td class="text-center">{{ $pay->adv_deduction_amount }}</td>
                    <td class="text-center">{{ $pay->adv_balance }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    @php
        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::SPELLOUT);
        $words = $numberFormatter->format($pay->netSalary);
    @endphp
    <div class="footer">
        <table style="width:100%;">
            <tr>
                <td colspan="5">Net Salary <span class="strong">:{{ $pay->netSalary }} ({{ ucwords("Rupees ". $words . " Only") }})</span></td>
            </tr>
            <tr>
                <td>Working Days :{{ $pay->workingDays }}</td>
                <td>Absenties :{{ $pay->absenties }}</td>
                <td>Absenty Fine Rate :{{ $pay->fineRate}}</td>
                <td>Sales :{{ $pay->sales }}</td>
                <td>Sale Returns :{{ $pay->returns }}</td>
            </tr>

        </table>
    </div>
</div>
</body>
</html>
<script src="{{ asset('src/plugins/src/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
    window.print();

        setTimeout(function() {
            window.history.back();
    }, 5000);

</script>
