@extends('layouts.admin')
@section('title', 'Unit Index')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Units
            </h3>
            @can('Add Unit')
            <div class="card-actions">
                <a href="{{route('unit.create')}}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Add Unit
                </a>
            </div>
            @endcan

        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Unit Name</th>
                    <th>Unit Value</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($units  as $unit)
                    <tr>
                        <td>{{ $unit->unitID }}</td>
                        <td>{{ $unit->name }}</td>
                        <td>{{ $unit->value }}</td>

                        <td>

                            <a class="ps-1 pe-1" href="{{ route('unit.edit', $unit->unitID) }}">
                                <i class="text-yellow fa fa-edit"></i>
                            </a>
                            <form action="{{ route('unit.destroy', $unit->unitID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?');" style="display: inline-block;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <a class="ps-1 pe-1" href="javascript:void(0);" onclick="$(this).closest('form').submit();">
                                    <i class="text-red fa fa-trash"></i>
                                </a>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

