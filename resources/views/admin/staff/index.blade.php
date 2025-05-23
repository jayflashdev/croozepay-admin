@extends('admin.layouts.master')
@section('title', 'Staffs')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">@lang('All Staffs') </h5>
                <a class="btn btn-primary btn-sm" href="{{ route('admin.staffs.create') }}"><i class="fas fa-plus"></i>
                    @lang('Create Staff') </a>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover " id="datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Email Address')</th>
                        <th>@lang('Phone')</th>
                        <th>@lang('Role')</th>
                        <th>@lang('Options')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staffs as $staff)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $staff->name ?? 'Deleted' }}</td>
                            <td>{{ $staff->email ?? 'Deleted' }}</td>
                            <td>{{ $staff->phone ?? 'none' }}</td>
                            <td>
                                @if ($staff->type == 'super')
                                    <span class="badge bg-primary"> super admin</span>
                                @else
                                    <span class="badge bg-warning"> {{ $staff->role->name ?? "NA"}}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a class="btn btn-primary btn-sm btn-circle"
                                    href="{{ route('admin.staffs.edit', $staff->id) }}" title="@lang('Edit') ">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if ($staff->type != 'super')
                                <a class="btn btn-danger btn-circle btn-sm delete-btn"
                                    href="{{ route('admin.staffs.destroy', $staff->id) }}" title="@lang('Delete')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                @endif
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
