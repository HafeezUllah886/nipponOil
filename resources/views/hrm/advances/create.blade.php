@extends('layouts.admin')
@section('title', 'Create Payroll')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Add New Payroll
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('/hrm/payroll/store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="emp">Employee</label>
                            <select name="emp" id="emp" required onchange="getSalary()" class="selectize">
                                <option value="">Select Employee</option>
                                @foreach ($emps as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" name="date" required value="{{ date('Y-m-d') }}" id="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="account">Account</label>
                           <select name="account" class="selectize" id="account">
                            @foreach ($accounts as $account)
                            <option value="{{ $account->accountID }}">{{ $account->name }}</option>
                        @endforeach
                           </select>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="salary">Salary</label>
                           <input type="number" name="salary" min="1" id="salary" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="notes">Notes</label>
                           <textarea name="notes" class="form-control" id="notes"></textarea>
                        </div>
                    </div>

                    <div class="col-12 mt-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Create</button>
                    </div>

                </div>

            </form>
        </div>
    </div>
@endsection
@section('more-script')
<script src="https://code.jquery.com/jquery-3.5.1.js" ></script>
<script src="{{ asset('src/plugins/src/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script>
     $('.selectize').selectize()[0].selectize;

            function getSalary(){
            var emp = $("#emp").find(":selected").val();
            $.ajax({
                url: "{{ url('/hrm/payroll/getSalary/') }}/"+emp,
                method: "GET",
                success: function (response){
                    $("#salary").val(response);
                }
            });
        }

</script>
@endsection





