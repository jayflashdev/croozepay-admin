@extends('admin.layouts.master')
@section('title', "Recharge PIN")

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('Recharge Card Plan') </h5>
        </div>
    </div>
   <div class="card-body table-responsive">
    <table class="table table-hover table-bordered datatable-init" width="100%" >
        <thead>
            <tr>
                <th>@lang('ID ')</th>
                <th>@lang('Network')</th>
                <th>@lang('Amount')</th>
                {{-- <th>Amount</th> --}}
                <th>@lang('Status')</th>
                <th>@lang('Options')</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plans as $key => $plan)
            <tr>
                <td>{{$plan->id}}</td>
                <td>{{$plan->network->name ?? "None"}}</td>
                <td>{{$plan->value}}</td>
                {{-- <td>{{$plan->name}}</td> --}}
                <td>
                    <span class="badge {{$plan->status == 1 ?'bg-success': "bg-danger"}}">{{$plan->status == 1 ?'active': "disabled"}}</span>
                </td>
                <td>
                    <a class="btn btn-primary btn-sm btn-circle" href="{{route('admin.plan.recharge.edit', $plan->id)}}" title="@lang('Edit') ">
                        <i class="icon fa fa-edit"></i>
                    </a>
                    @if($plan->status == 1)
                    <a class="btn btn-sm btn-danger" href="{{route('admin.plan.recharge.plan.status' ,[$plan->id, 0])}}">Disable</a> @else
                    <a class="btn btn-sm btn-success" href="{{route('admin.plan.recharge.plan.status' ,[$plan->id, 1])}}">Enable</a>
                    @endif
                </td>
            </tr>

        @endforeach
        </tbody>
    </table>
   </div>
</div>

@endsection
