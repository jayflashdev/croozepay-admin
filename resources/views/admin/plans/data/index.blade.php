@extends('admin.layouts.master')
@section('title', "Data Plans")

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('Data Plans') </h5>
            <a href="{{route('admin.plan.data.create')}}" class="btn btn-primary">Create Plan</a>
        </div>
    </div>
   <div class="card-body table-responsive">
    <table class="table table-hover datatable-init" width="100%" >
        <thead>
            <tr>
                <th>@lang('Network')</th>
                <th>@lang('ID')</th>
                <th>@lang('Plan Name')</th>
                <th>@lang('Type')</th>

                <th>@lang('Price')</th>
                <th>@lang('Reseller')</th>
                <th>@lang('API')</th>

                <th>@lang('Days')</th>
                <th>@lang('Status')</th>
                <th>@lang('Options')</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plans as $key => $plan)
            <tr>
                <td>{{$plan->network->name ?? "Null"}}</td>
                <td>{{$plan->id}}</td>
                <td>{{$plan->name}} {{$plan->size}}</td>
                <td>{{$plan->type}}</td>

                <td>{{$plan->price}}</td>
                <td>{{$plan->reseller}}</td>
                <td>{{$plan->api}}</td>

                <td>{{$plan->day}}</td>
                <td>
                    <span class="badge {{$plan->status == 1 ?'bg-success': "bg-warning"}}">{{$plan->status == 1 ?'active': "disabled"}}</span>
                </td>
                <td>
                    <a class="btn btn-primary btn-sm btn-circle" href="{{route('admin.plan.data.edit', $plan->id)}}" title="@lang('Edit') ">
                        <i class="icon fa fa-edit"></i>
                    </a>
                </td>
            </tr>

        @endforeach
        </tbody>
    </table>
   </div>
</div>

@endsection
