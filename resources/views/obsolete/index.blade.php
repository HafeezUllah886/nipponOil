@extends('layouts.admin')
@section('title', 'Obsolete Inventory Index')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Obsolete Inventory
            </h3>
            <div class="card-actions">
                <a href="{{ url('/recondition') }}" class="btn btn-success d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Reconditioned Products
                </a>
                <a href="{{ url('/obsolete/create') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Create New
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Product</th>
                   {{--  <th>Batch</th>
                    <th>Expiry</th> --}}
                    <th>Quantity</th>
                    <th>Reason</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($obsolets as $obsolet)
                        <tr>
                            <td>{{$obsolet->id}} <input type="hidden" id="data_{{$obsolet->refID}}"
                                data-date="{{$obsolet->date}}"
                                data-name="{{$obsolet->product->name}}"
                                data-qty="{{$obsolet->quantity}}"
                                data-availQty="{{$obsolet->availQty}}"
                                data-reason="{{$obsolet->reason}}">
                            </td>
                            <td>{{$obsolet->date}}</td>
                            <td>{{$obsolet->product->name}} ({{ $obsolet->product->brand->name }})</td>
                           {{--  <td>{{$obsolet->batchNumber}}</td>
                            <td>{{$obsolet->expiry}}</td> --}}
                            <td>{{$obsolet->quantity}}</td>
                            <td>{{$obsolet->reason}}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $obsolet->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{$obsolet->id }}">
                                        <a class="dropdown-item" onclick="edit({{$obsolet->refID}})" href="#">
                                            Edit
                                        </a>
                                        <a class="dropdown-item" href="{{ url('/recondition/create/') }}/{{ $obsolet->id }}">
                                            Recondition
                                        </a>
                                        <a class="dropdown-item text-danger" href="{{ url('/obsolete/delete/') }}/{{ $obsolet->refID }}">
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
   <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{url('/obsolete/update')}}" method="post">
            <div class="modal-body">
                @csrf
            <div class="form-group">
                <label for="edit_name">Product</label>
                <input type="hidden" id="edit_ref" name="ref" class="form-control">
                <input type="text" disabled id="edit_name" class="form-control">
            </div>
            <div class="form-group">
                <label for="edit_date">Date</label>
                <input type="date" id="edit_date" required name="date" class="form-control">
            </div>
            <div class="form-group">
                <label for="edit_qty">Quanitity</label>
                <input type="number" id="edit_qty" min="1" required name="qty" class="form-control">
            </div>
            <div class="form-group">
                <label for="edit_reason">Reason</label>
                <input type="text" id="edit_reason" required name="reason" class="form-control">
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
        </div>
    </div>

@endsection
@section('more-script')
    <script>
        function edit(ref)
        {
            var date = $("#data_"+ref).attr("data-date");
            var name = $("#data_"+ref).attr("data-name");
            var qty = $("#data_"+ref).attr("data-qty");
            var availQty = $("#data_"+ref).attr("data-availQty");
            var reason = $("#data_"+ref).attr("data-reason");

            $("#edit_ref").val(ref);
            $("#edit_date").val(date);
            $("#edit_name").val(name);
            $("#edit_qty").val(qty);
            $("#edit_qty").attr("max", availQty);
            $("#edit_reason").val(reason);
            $("#editModal").modal("show");
        }
    </script>
@endsection



