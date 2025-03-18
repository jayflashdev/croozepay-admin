@extends('admin.layouts.master')
@section('title', "Cable Plans")

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('Cable Plans') </h5>
            <a class="btn btn-primary btn-sm" href="{{route('admin.plan.cable.create')}}"><i class="fas fa-plus"></i> @lang('Add Plan') </a>
        </div>
    </div>
   <div class="card-body table-responsive">
    <table class="table table-hover datatable-init" >
        <thead>
            <tr>
                <th>@lang('Cable')</th>
                <th>@lang('Plan ID')</th>
                <th>@lang('Plan Name')</th>
                <th>@lang('Price')</th>
                <th>@lang('Status')</th>
                <th>@lang('Options')</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plans as $key => $plan)
            <tr>
                <td>{{$plan->decoder->name ?? ""}}</td>
                <td>{{$plan->id}}</td>
                <td>{{$plan->name}}</td>
                <td>{{format_price($plan->price)}}</td>
                <td><span class="badge @if($plan->status == 1)bg-success @else bg-danger @endif">@if($plan->status == 1)active @else disabled @endif </span>
                </td>
                <td>
                    <a class="btn btn-primary btn-sm btn-circle" href="{{route('admin.plan.cable.edit', $plan->id)}}" title="@lang('Edit') ">
                        <i class="icon fa fa-edit"></i>
                    </a>
                    <a class="btn btn-danger btn-sm btn-circle delete-btn" href="{{route('admin.plan.cable.delete', $plan->id)}}" title="@lang('Delete') ">
                        <i class="icon fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
   </div>
</div>

@endsection
