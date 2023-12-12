@extends('layouts.admin')
@section('title', 'Visit View')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Visit to {{ ucfirst($visit->visit_to) }}
            </h3>

        </div>
        <div class="card-body">
            <dt>
                <p class=" fs-5">Visit By: {{ $visit->employee->name}}</p>
                <p class=" fs-5">Visit To: {{ $visit->visit_to}}</p>
                <p class=" fs-5">Date: {{ $visit->date}}</p>
                <p class=" fs-5">Expense: Rs. {{ $visit->exp}}</p>
                <p class=" fs-5">Expense From: {{ $visit->expAccount->name}}</p>
                <p class=" fs-5">Notes: {{ $visit->notes}}</p>

            </dt>
        </div>
    </div>

@endsection
