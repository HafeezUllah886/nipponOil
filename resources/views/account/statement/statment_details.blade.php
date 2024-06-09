<div class="row d-flex justify-content-end mb-2">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6>Previous Balance: {{number_format($p_balance,3)}}</h6>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6>Current Balance: {{number_format($c_balance, 3)}}</h6>
            </div>
        </div>
    </div>
   </div>
<table class="table table-hover table-border" id="table">
    <thead>
        <th>#</th>
        <th>Ref#</th>
        <th>Date</th>
        <th>Type</th>
        <th>Description</th>
        <th class="text-end">Cradit +</th>
        <th class="text-end">Debit -</th>
        <th class="text-end">Balance</th>
    </thead>
    <tbody >
        @php
            $total_cr = 0;
            $total_db = 0;
            $balance = $p_balance;
        @endphp
        @foreach ($items as $item)
        @php
            $total_cr += $item->credit;
            $total_db += $item->debt;
            $balance -= $item->debt;
            $balance += $item->credit;

        @endphp
        <tr>
        <td>{{ $item->transactionID }}</td>
        <td>{{ $item->refID }}</td>
        <td>{{ date("d M Y",strtotime($item->date)) }}</td>
        <td>{{$item->type }}</td>
        <td>{!! $item->description !!}</td>
        <td class="text-end">{{ $item->credit == null ? '-' : number_format($item->credit,3)}}</td>
        <td class="text-end">{{ $item->debt == null ? '-' : number_format($item->debt,3)}}</td>
        <td class="text-end">{{ number_format($balance,3) }}</td>

        </tr>
    @endforeach

</tbody>
</table>
  <script src="{{ asset('assets/src/plugins/src/table/datatable/datatables.js') }}"></script>
<script>
     $('#table').DataTable({
                "order": [[0, 'desc']],
                'columnDefs': [
                { 'sortable': false, 'searchable': false, 'visible': false, 'type': 'num', 'targets': [0] }
                ],
            });

            $("th").removeClass('sorting');
            $("th").removeClass('sorting_desc');
</script>
