@extends('layouts.admin')
@section('title', 'Visit Create')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Add New Visit
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('/visits/store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mt-3">
                            <label for="visit_by" class="form-label">Visit By: </label>
                            <select name="visit_by" class="form-select">
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group mt-3">
                            <label for="visit_to" class="form-label">Visit to: </label>
                            <input type="text" required name="visit_to" class="form-control">
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group mt-3">
                            <label for="date" class="form-label">Date: </label>
                            <input type="date" required value="{{ date("Y-m-d") }}" name="date" class="form-control">
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group mt-3">
                            <label for="exp" class="form-label">Expense: </label>
                            <input type="number" required name="exp" class="form-control">
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group mt-3">
                            <label for="account" class="form-label">Expense From: </label>
                            <select name="account" class="form-select">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->accountID }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="notes" class="form-label">Notes: </label>
                                <textarea name="notes" id="notes" class="form-control" cols="30" rows="5"></textarea>
                            </div>
                            </div>
                    </div>
                    <div class="form-group row">

                       <div class="col-md-6">
                        <h6>Re-visit Reminder</h6>
                        <div class="switch form-switch-custom switch-inline form-switch-primary">
                            <input class="switch-input" name="reminder" type="checkbox" role="switch" id="form-custom-switch-primary" >
                            <label class="switch-label" for="form-custom-switch-primary">Enable Reminder</label>
                        </div>
                       </div>
                        <label for="account" class="form-label col-form-label col-sm-12 col-md-6 col-lg-4"> Reminder Date:
                           <input type="date" name="due" min="{{ date("Y-m-d") }}" id="due" class="form-control">
                        </label>
                    </div>

                    <div class="row mt-3">
                        <div class="col d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>

                </div>

            </form>
        </div>
    </div>
@endsection
@section('more-css')
    <link rel="stylesheet" href="{{ asset('src/assets/css/light/forms/switches.css') }}">
    <link rel="stylesheet" href="{{ asset('src/assets/css/dark/forms/switches.css') }}">

@endsection
@section('more-script')
    <script>
        $('#form-custom-switch-primary').on('change', function(){
            if ($(this).is(":checked")) {
                $("#due").attr('required', true);
        } else {
            $("#due").attr('required', false);
        }
        });
    </script>
@endsection
