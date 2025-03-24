@extends('admin.layouts.master')
@section('title', "Airtime Plans")

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('Airtime Plans') </h5>
        </div>
    </div>
   <div class="card-body table-responsive">
    <table class="table table-bordered table-hover datatable-init" >
        <thead>
            <tr>
                <th>@lang('ID')</th>
                <th>@lang('Network')</th>
                <th>@lang('Image')</th>
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
                <td><span class="badge @if($plan->airtime == 1)bg-success @else bg-danger @endif">@if($plan->airtime == 1)active @else disabled @endif </span></td>
                <td>
                    <div class="dropstart">
                        <button class="btn btn-light" type="button" id="" data-bs-toggle="dropdown">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#planEdit{{$plan->id}}" href="#" >@lang('Edit')</a>
                            @if($plan->airtime == 1)
                            <a class="dropdown-item" href="{{route('admin.plan.airtime.status' ,[$plan->id, 0])}}">Disable</a> @else
                            <a class="dropdown-item" href="{{route('admin.plan.airtime.status' ,[$plan->id, 1])}}">Enable</a>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            {{-- modal --}}
            <div class="modal fade" id="planEdit{{$plan->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h6 class="modal-title" id="myModalLabel"> Edit {{$plan->name}}</h6>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                      </div>
                      <div class="modal-body row">
                        <form action="{{route('admin.plan.airtime.update', $plan->id)}}" class="row" method="post">
                            @csrf
                            <div class="col-md-3 form-group">
                                <label class="form-label">Network</label>
                                <input type="text"class="form-control" required name="name" placeholder="Network" value="{{($plan->name)}}" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Airtime Discount</label>
                                <input type="text" class="form-control" value="{{$plan->discount}}" name="discount" placeholder="discount" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">API Discount</label>
                                <input type="text" class="form-control" value="{{$plan->api_discount}}" name="api_discount" placeholder="discount" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Reseller Discount</label>
                                <input type="text" class="form-control" value="{{$plan->reseller}}" name="reseller" placeholder="discount" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Msorg 1</label>
                                <input type="text"class="form-control" value="{{($plan->msorg1)}}" name="msorg1" placeholder="msorg1" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Msorg 2</label>
                                <input type="text"class="form-control" value="{{($plan->msorg2)}}" name="msorg2" placeholder="msorg2" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Msorg 3</label>
                                <input type="text"class="form-control" value="{{($plan->msorg3)}}" name="msorg3" placeholder="msorg3" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Msorg 4</label>
                                <input type="text"class="form-control" value="{{($plan->msorg4)}}" name="msorg4" placeholder="msorg4" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Msorg 5</label>
                                <input type="text"class="form-control" value="{{($plan->msorg5)}}" name="msorg5" placeholder="msorg5" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Msorg 6</label>
                                <input type="text"class="form-control" value="{{($plan->msorg6)}}" name="msorg6" placeholder="msorg6" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Adex 1</label>
                                <input type="text"class="form-control" value="{{($plan->adex1)}}" name="adex1" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Adex 2</label>
                                <input type="text"class="form-control" value="{{($plan->adex2)}}" name="adex2" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Adex 3</label>
                                <input type="text"class="form-control" value="{{($plan->adex3)}}" name="adex3" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Adex 4</label>
                                <input type="text"class="form-control" value="{{($plan->adex4)}}" name="adex4" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Adex 5</label>
                                <input type="text"class="form-control" value="{{($plan->adex5)}}" name="adex5" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Adex 6</label>
                                <input type="text"class="form-control" value="{{($plan->adex6)}}" name="adex6" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">VTpass</label>
                                <input type="text"class="form-control" value="{{($plan->vtpass)}}" name="vtpass" placeholder="Vtpass" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">ClubKonnect</label>
                                <input type="text"class="form-control" value="{{($plan->clubkonnect)}}" name="clubkonnect" placeholder="clubKonnect" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Easyaccess</label>
                                <input type="text"class="form-control" value="{{($plan->easyaccess)}}" name="easyaccess" placeholder="Easy Access" >
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label">Ncwallet</label>
                                <input type="text"class="form-control" value="{{($plan->ncwallet)}}" name="ncwallet" placeholder="Ncwallet" >
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
