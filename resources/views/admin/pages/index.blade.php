@extends('admin.layouts.master')
@section('title', 'Pages')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('All Pages')</h5>
            <a class="btn btn-primary btn-sm" href="{{route('admin.pages.create')}}"><i class="fas fa-plus"></i> @lang('Add') </a>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover" id="datatable">
            <thead>
                <tr>                       
                    <th>@lang('Name')</th>
                    <th>@lang('URL')</th>
                    <th>@lang('Actions')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $item)
                <tr>                       
                    <td>{{$item->title}}</td>
                    <td>
                        @if($item->type == 'custom')
                        <a href="{{ route('index') }}/page/{{ $item->slug }}">{{ route('index') }}/page/{{ $item->slug }}</a>
                        @else
                        <a href="{{ route('index') }}/{{ $item->slug }}">{{ route('index') }}/{{ $item->slug }}</a>
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-primary btn-sm btn-circle" href="{{route('admin.pages.edit', $item->id)}}" title="@lang('Edit')">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($item->type == 'custom')
                        <a class="btn btn-danger btn-sm btn-circle" href="{{route('admin.pages.delete', $item->id)}}" title="@lang('Delete')">
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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@endsection