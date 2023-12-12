@extends('layouts.admin')
@section('title', 'Warehouse Index')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Warehouses
            </h3>
            <div class="card-actions">
                @can('Add Warehouse')
                <a href="{{route('warehouse.create')}}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Add Warehouse
                </a>
                @endcan

            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Logo</th>
                    <th>Warehouse Name</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($warehouses  as $warehouse)
                    <tr>
                        <td>{{ $warehouse->warehouseID }}</td>
                        <td><img src="{{ asset($warehouse->logo) }}" alt="logo" style="width:150px;"></td>
                        <td>{{ $warehouse->name }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle form-select" type="button" id="dropdownMenuButton_{{ $warehouse->warehouseID }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $warehouse->warehouseID }}">
                                        <a class="dropdown-item" href="{{ route('warehouse.show', $warehouse->warehouseID) }}">
                                         View
                                        </a>
                                    @can('Edit Warehouse')
                                        <a class="dropdown-item" href="{{ route('warehouse.edit', $warehouse->warehouseID) }}">
                                         Edit
                                        </a>
                                    @endcan
                                   {{--  @can('Delete Warehouse')
                                        <a class="dropdown-item text-danger" href="{{ url('/warehouse/delete/') }}/{{$warehouse->warehouseID}}">
                                         Delete
                                        </a>
                                    @endcan --}}
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

