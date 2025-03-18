@extends('admin.layouts.master')
@section('title', 'Email Templates')

@section('content')
<div class="card">
    <div class="card-body table-responsive">
        <table class="table-hover table table-bordered" id="datatable">
            <thead>
                <tr>
                    <th>@lang('Type')</th>
                    <th>@lang('Subject')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $template)
                    <tr>
                        <td>{{ $template->type }}</td>
                        <td>{{ $template->subject }}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ route('admin.email.edit_template', $template->id) }}" data-bs-toggle="tooltip" title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-muted text-center" colspan="100%">No Email Template was found</td>
                    </tr>
                @endforelse
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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@endsection