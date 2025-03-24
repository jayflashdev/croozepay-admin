@extends('admin.layouts.master')
@section('title', "Electricity Plans")

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('Power Companies') </h5>
            <a class="btn btn-primary btn-sm" href="{{route('admin.plan.electricity.create')}}"><i class="fas fa-plus"></i> @lang('Add Plan') </a>
        </div>
    </div>
   <div class="card-body table-responsive">
    <table class="table table-hover datatable-init" >
        <thead>
            <tr>
                <th>ID</th>
                <th>@lang('Name')</th>
                <th>@lang('Image')</th>
                <th>Charges</th>
                <th>@lang('Status')</th>
                <th>@lang('Options')</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plans as $key => $plan)
            <tr>
                <td>{{$plan->id}}</td>
                <td>{{$plan->name}}</td>
                <td><img loading="lazy"  class="img-table" src="{{my_asset($plan->image)}}" alt="{{$plan->name}}"> </td>
                <td> {{format_price($plan->fee)}}</td>
                <td><span class="badge @if($plan->status == 1)bg-success @else bg-danger @endif">@if($plan->status == 1)active @else disabled @endif </span>
                </td>
                <td>
                    <div class="dropstart">
                        <button class="btn btn-light" type="button" id="" data-bs-toggle="dropdown">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{route('admin.plan.electricity.edit', $plan->id)}}" >@lang('Edit Plan')</a>
                            @if($plan->status == 1)
                            <a class="dropdown-item" href="{{route('admin.plan.electricity.status' ,[$plan->id, 0])}}">Disable</a> @else
                            <a class="dropdown-item" href="{{route('admin.plan.electricity.status' ,[$plan->id, 1])}}">Enable</a>
                            @endif
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

@section('breadcrumb')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">@yield('title')</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Bills</a></li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@endsection

@section('scripts')
@endsection
@section('styles')
<style>
    .img-table{ height:40px ;}
    .card-header{background-color: #fefefe; border-bottom:1px solid #949d94 }
</style>
@endsection
