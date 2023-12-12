<div class="row d-flex justify-content-end mb-2">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6>Present: {{$present}}</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6>Absent: {{$absent}}</h6>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6>Late: {{$late}}</h6>
            </div>
        </div>
    </div>
   </div>
<table class="table table-hover table-border" id="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Status</th>
            <th>Check IN</th>
            <th>Check Out</th>
            <th>Notes</th>
            <th>Marked By</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($items as $att)
            @php
                $color = null;
                if($att->status == 'Present')
                {
                    $color = "badge-success";
                }
                elseif($att->status == "Absent")
                {
                    $color = "badge-danger";
                }
                elseif($att->status == "Late")
                {
                    $color = "badge-warning";
                }
                elseif($att->status == "Short Leave")
                {
                    $color = "badge-primary";
                }
                elseif($att->status == "Leave")
                {
                    $color = "badge-info";
                }
            @endphp
                <tr>

                    <td>{{ $att->id}}</td>
                    <td>{{ date("d M Y", strtotime($att->date))}}</td>
                    <td> <span class="badge {{$color}}"> {{ $att->status}}</span></td>
                    <td> {{ $att->in ? date("h:i A", strtotime($att->in)) : '-'}}</td>
                    <td> {{ $att->out ? date("h:i A", strtotime($att->out)) : '-'}}</td>
                    <td> {{ $att->notes}}</td>
                    <td>{{ $att->createdBy}}</td>

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
