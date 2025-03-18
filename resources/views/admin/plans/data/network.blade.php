@extends('admin.layouts.master')
@section('title', 'Data Plans')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="fw-bold">All Networks</h5>
        <a href="{{route('admin.plan.data')}}" class="btn btn-primary">All Plans</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover" id="datatable">
            <thead>
                <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Image</th>
                <th>Type</th>
                <th>Plans</th>
                <th>Status</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plans as $key => $plan)
                <tr>
                    <td>{{$key +1}}</td>
                    <td> <a href="{{route('admin.plan.data.plans', $plan->id)}}">{{$plan->name}}</a></td>
                    <td><img loading="lazy"  class="img-table" src="{{my_asset($plan->image)}}" alt="{{$plan->name}}"> </td>
                    <td>
                        <p class="mb-0">CG: <span class="badge @if($plan->data_cg == 1)bg-success @else bg-danger @endif">@if($plan->data_cg == 1)active @else disabled @endif </span></p>
                        <p class="mb-0">Gifting: <span class="badge @if($plan->data_g == 1)bg-success @else bg-danger @endif">@if($plan->data_g == 1)active @else disabled @endif </span></p>
                        <p class="mb-0">SME: <span class="badge @if($plan->data_sme == 1)bg-success @else bg-danger @endif">@if($plan->data_sme == 1)active @else disabled @endif </span></p>
                    </td>
                    <td> {{$plan->datasub->count()}}</td>
                    <td><span class="badge @if($plan->data == 1)bg-success @else bg-danger @endif">@if($plan->data == 1)active @else disabled @endif </span></td>
                    <td>
                        <div class="dropstart">
                        <button class="btn btn-light" type="button" id="" data-bs-toggle="dropdown">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#planEdit{{$plan->id}}" href="#" >@lang('Edit')</a>
                            <a class="dropdown-item" href="{{route('admin.plan.data.plans', $plan->id)}}" >Data Plans</a>
                            @if($plan->data == 1)
                            <a class="dropdown-item" href="{{route('admin.plan.data.status' ,[$plan->id, 0])}}">Disable</a> @else
                            <a class="dropdown-item" href="{{route('admin.plan.data.status' ,[$plan->id, 1])}}">Enable</a>
                            @endif
                        </div>
                        </div>
                    </td>
                </tr>

                <div class="modal fade" id="planEdit{{$plan->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="myModalLabel"> Edit {{$plan->name}} Data</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                        </div>
                        <div class="modal-body row">
                            <form action="{{route('admin.plan.airtime.update', $plan->id)}}" class="row" method="post">
                                @csrf
                                <div class="col-md-4 form-group">
                                    <label for="dataCgStatus" class="form-label">CG Data</label>
                                    <select class="form-select" id="dataCgStatus" name="data_cg">
                                        <option value="1" {{ $plan->data_cg ? 'selected' : '' }}>Enabled</option>
                                        <option value="0" {{ !$plan->data_cg ? 'selected' : '' }}>Disabled</option>
                                    </select>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="dataSmeStatus" class="form-label">SME Data</label>
                                    <select class="form-select" id="dataSmeStatus" name="data_sme">
                                        <option value="1" {{ $plan->data_sme ? 'selected' : '' }}>Enabled</option>
                                        <option value="0" {{ !$plan->data_sme ? 'selected' : '' }}>Disabled</option>
                                    </select>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="dataGStatus" class="form-label">Gifting Data</label>
                                    <select class="form-select" id="dataGStatus" name="data_g">
                                        <option value="1" {{ $plan->data_g ? 'selected' : '' }}>Enabled</option>
                                        <option value="0" {{ !$plan->data_g ? 'selected' : '' }}>Disabled</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-block">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
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
    .img-table{ height:50px ;}
    .card-header{background-color: #fefefe; border-bottom:1px solid #949d94 }


</style>
@endsection
