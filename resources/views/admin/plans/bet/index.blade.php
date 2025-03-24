@extends('admin.layouts.master')
@section('title', "Bet Plans")

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('Bet Plans') </h5>
            <a class="btn btn-primary btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#createBet"><i class="fas fa-plus"></i> Add</a>
            {{-- <a class="btn btn-primary btn-sm" href="{{route('admin.plan.bet.create')}}"><i class="fas fa-plus"></i> @lang('Add Plan') </a> --}}
        </div>
    </div>
   <div class="card-body table-responsive">
    <table class="table table-hover table-bordered datatable-init" >
        <thead>
            <tr>
                <th>@lang('Bet Name')</th>
                <th>@lang('ID')</th>
                <th>Image</th>
                <th>Fees</th>
                <th>@lang('Status')</th>
                <th>@lang('Options')</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plans as $key => $plan)
            <tr>
                <td>{{$plan->name}}</td>
                <td>{{$plan->id}}</td>
                <td><img loading="lazy"  class="img-table" src="{{my_asset($plan->image)}}" alt="{{$plan->name}}"> </td>
                <td> {{format_price($plan->fee)}}</td>
                <td><span class="badge @if($plan->status == 1)bg-success @else bg-danger @endif">@if($plan->status == 1)active @else disabled @endif </span>
                </td>
                <td>
                    <a class="btn btn-primary btn-sm btn-circle" data-bs-toggle="modal" data-bs-target="#planDetail{{$plan->id}}" href="#" title="@lang('Edit') ">
                        <i class="icon fa fa-edit"></i>
                    </a>
                </td>
            </tr>
            <div class="modal fade" id="planDetail{{$plan->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h6 class="modal-title" id="myModalLabel"> Edit Bet Plan</h6>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                      </div>
                      <div class="modal-body row">
                        <form action="{{route('admin.plan.bet.update', $plan->id)}}" class="row" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6 form-group">
                                <label class="form-label">Bet Name</label>
                                <input type="text"class="form-control" required name="name" placeholder="Bet Name" value="{{($plan->name)}}" >
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-label">Status</label>
                                <select class="form-select " name="status">
                                    <option value="1">Choose</option>
                                    <option @if($plan->status == 1) selected @endif value="1" > Active</option>
                                    <option @if($plan->status != 1) selected @endif value="2"> Disabled</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-6 form-group">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" accept="image/*" id="imgInp" class="form-control" >
                            </div>
                            <div class="form-group col-md-6 col-6">
                                <label class="form-label">Charges/Fee</label>
                                <input type="number" class="form-control" value="{{($plan->fee)}}" name="fee" placeholder="Plan Charges" required>
                            </div>
                            <div class="col-md-6 col-6 form-group">
                                <label class="form-label">Ncwallet</label>
                                <input type="text"class="form-control" value="{{($plan->ncwallet)}}" name="ncwallet" placeholder="ncwallet" >
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-label">ClubKonnect</label>
                                <input type="text"class="form-control" value="{{($plan->clubkonnect)}}" name="clubkonnect" placeholder="clubKonnect" >
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

<div class="modal fade" id="createBet" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title fw-bold">Create Plan</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.plan.bet.create')}}" class="row" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-md-4 col-6 form-group">
                    <label class="form-label">Bet Name</label>
                    <input type="text"class="form-control" name="name" placeholder="Bet Name" value="{{old('name')}}" >
                </div>
                <div class="col-md-4 col-6 form-group">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" accept="image/*" id="imgInp" class="form-control" >
                </div>
                <div class="form-group col-md-4 col-6">
                    <label class="form-label">Charges/Fee</label>
                    <input type="number" class="form-control" name="fee" placeholder="Plan Charges" required>
                </div>
                <div class="col-md-4 col-6 form-group">
                    <label class="form-label">Ncwallet</label>
                    <input type="text"class="form-control" name="ncwallet" placeholder="ncwallet" >
                </div>
                <div class="col-md-4 col-6 form-group">
                    <label class="form-label">ClubKonnect</label>
                    <input type="text"class="form-control" name="clubkonnect" placeholder="clubkonnect" >
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-block">Create</button>
                </div>
            </form>
        </div>
        </div>
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
