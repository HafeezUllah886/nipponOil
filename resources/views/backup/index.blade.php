@extends('layouts.admin')
@section('title', 'Database Backup')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-user-graduate"></i> Database Backups
            </h3>
            <div class="card-actions">
                <a href="{{url('/backup/create')}}" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="fas fa-plus"></i> Create
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover datatable display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Created at</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                @foreach($data as $key=>$item)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $item->created_at}}</td>

                        <td>

                            <a class="btn btn-danger" href="{{ url('/backup/delete/') }}/{{$item->id}}">
                                delete
                            </a>
                            <a class="btn btn-info" href="{{ url('/backup/download/') }}/{{$item->id}}">
                                Download
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>


            </table>
        </div>
    </div>
@endsection

