@extends('admin.layouts.master')
@section('title', 'Roles')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('All Roles') </h5>
            <a class="btn btn-primary btn-sm" href="{{route('admin.roles.create')}}"><i class="fas fa-plus"></i> @lang('Create Role') </a>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover table-bordered" id="datatable">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th>@lang('Name')</th>
                    <th width="15%">@lang('Actions')</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach($roles as $key => $role)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{$role->name}}</td>
                        <td class="text-right">
                            <a class="btn btn-primary btn-sm btn-circle" href="{{route('admin.roles.edit', $role->id)}}" title="@lang('Edit')">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a class="btn btn-danger btn-circle btn-sm" href="{{route('admin.roles.destroy', $role->id)}}" title="@lang('Delete')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('breadcrumb')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">@yield('title')</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Staffs</a></li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@endsection