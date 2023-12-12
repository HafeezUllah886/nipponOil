@extends('layouts.admin')
@section('title', 'Recondition Create')
@section('content')
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h4 class="card-title fw-semibold">
                <i class="fas fa-users-cog"></i> Products Reconditioning
            </h4>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ url('/recondition/store') }}" method="POST">
                @csrf
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="product" class="form-label">Product: </label>
                                <input type="hidden" value="{{ $obsolete->id }}" name="obsoleteID">
                                <input type="hidden" value="{{ $obsolete->productID }}" name="productID">
                                <input type="text" disabled value="{{ $obsolete->product->name }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="qty" class="form-label">Quantity: (Max: {{ $obsolete->qty }})</label>
                                <input type="number" value="1" min="1" max="{{ $obsolete->qty }}" required name="qty" class="form-control">
                            </div>
                        </div>
                        @if($obsolete->product->isExpire == 0)
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="batchNumber"  class="form-label">Batch Number: </label>
                                <input type="text" required name="batchNumber" value="{{ $obsolete->batchNumber }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="expiry"  class="form-label">Expiry: </label>
                                <input type="date" required name="expiry" value="{{ date("Y-m-d", strtotime($obsolete->expiry)) }}" class="form-control">
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="batchNumber" value="{{ $obsolete->batchNumber }}">
                        <input type="hidden" name="expiry" value="">
                        @endif
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="expense"  class="form-label">Expense: </label>
                                <input type="number" required name="expense" min="0" value="0" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="account" class="form-label">Expense From: </label>
                                <select name="accountID" class="form-select">
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->accountID }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="date" class="form-label">Date: </label>
                                <input type="date" value="{{ date("Y-m-d") }}" required name="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-3">
                                <label for="notes" class="form-label">Notes: </label>
                                <textarea name="notes" id="notes" class="form-control" cols="30" rows="5"></textarea>
                            </div>
                            </div>
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
