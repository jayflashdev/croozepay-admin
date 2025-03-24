@extends('admin.layouts.master')
@section('title', 'Education Services')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5> Education Services</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover" id="datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Price</th>
                        <th>API Price</th>
                        <th>Reseller</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plans as $key => $plan)
                        <tr>
                            <td>{{ $plan->id }}</td>
                            <td><img loading="lazy"  class="img-table" src="{{my_asset($plan->image)}}" alt="{{$plan->name}}"> </td>
                            <td>{{ $plan->name }}</td>
                            <td>{{ $plan->code ?? 'Null' }}</td>
                            <td>{{ format_price($plan->price) }}</td>
                            <td> {{ format_price($plan->api) }}</td>
                            <td> {{ format_price($plan->reseller) }}</td>
                            <td><span class="badge @if ($plan->status == 1) bg-success @else bg-danger @endif">
                                    @if ($plan->status == 1) active @else disabled @endif
                                </span>
                            </td>
                            <td>
                                <div class="dropstart">
                                    <button class="btn btn-light" type="button" id="" data-bs-toggle="dropdown">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit_modal-{{ $plan->id }}" href="#">@lang('Edit')</a>
                                        @if ($plan->status == 1)
                                            <a class="dropdown-item" href="{{ route('admin.plan.education.status', [$plan->id, 0]) }}">Disable</a>
                                        @else
                                            <a class="dropdown-item" href="{{ route('admin.plan.education.status', [$plan->id, 1]) }}">Enable</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        {{-- edit modals --}}
                        <div class="modal fade" id="edit_modal-{{ $plan->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Service</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.plan.education.update', $plan->id) }}" enctype="multipart/form-data" method="post">
                                            @csrf

                                            <div class="row">
                                                <div class="col-12 form-group">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" class="form-control" name="name" placeholder="Plan Name" value="{{ $plan->name }}" required>
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label class="form-label">Price</label>
                                                    <input type="number" class="form-control" name="price" value="{{ $plan->price }}" placeholder="Plan Price" required>
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label class="form-label">API Price</label>
                                                    <input type="number" class="form-control" name="api" value="{{ $plan->api }}" placeholder="API User Price"
                                                        required>
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label class="form-label">Reseller</label>
                                                    <input type="number" class="form-control" name="reseller" value="{{ $plan->reseller }}" placeholder="Reseller" required>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-4 col-md-3 form-group">
                                                    <label class="form-label">Adex 1</label>
                                                    <input type="text" class="form-control" name="adex1"  value="{{$plan->adex1 ?? ''}}">
                                                </div>
                                                <div class="col-4 col-md-3 form-group">
                                                    <label class="form-label">Adex 2</label>
                                                    <input type="text" class="form-control" name="adex2"  value="{{$plan->adex2 ?? ''}}">
                                                </div>
                                                <div class="col-4 col-md-3 form-group">
                                                    <label class="form-label">Adex 3</label>
                                                    <input type="text" class="form-control" name="adex3"  value="{{$plan->adex3 ?? ''}}">
                                                </div>
                                                <div class="col-4 col-md-3 form-group">
                                                    <label class="form-label">Adex 4</label>
                                                    <input type="text" class="form-control" name="adex4"  value="{{$plan->adex4 ?? ''}}">
                                                </div>
                                                <div class="col-4 col-md-3 form-group">
                                                    <label class="form-label">Adex 6</label>
                                                    <input type="text" class="form-control" name="adex5"  value="{{$plan->adex5 ?? ''}}">
                                                </div>
                                                <div class="col-4 col-md-3 form-group">
                                                    <label class="form-label">Adex 5</label>
                                                    <input type="text" class="form-control" name="adex6" value="{{$plan->adex6 ?? ''}}">
                                                </div>
                                                <div class="col-4 col-md-3 form-group">
                                                    <label class="form-label">Msorg 1</label>
                                                    <input type="text" class="form-control" name="msorg1" value="{{$plan->msorg1 ?? ''}}">
                                                </div>
                                                <div class="col-4 col-md-3 form-group">
                                                    <label class="form-label">Msorg 2</label>
                                                    <input type="text" class="form-control" name="msorg2" value="{{$plan->msorg2 ?? ''}}">
                                                </div>
                                                <div class="col-4 col-md-3 form-group">
                                                    <label class="form-label">Msorg 3</label>
                                                    <input type="text" class="form-control" name="msorg3" value="{{$plan->msorg3 ?? ''}}">
                                                </div>
                                                <div class="col-4 col-md-3 form-group">
                                                    <label class="form-label">Msorg 4</label>
                                                    <input type="text" class="form-control" name="msorg4" value="{{$plan->msorg4 ?? ''}}">
                                                </div>
                                                <div class="col-4 col-md-3 form-group">
                                                    <label class="form-label">Msorg 5</label>
                                                    <input type="text" class="form-control" name="msorg5" value="{{$plan->msorg5 ?? ''}}">
                                                </div>
                                                <div class="col-4 col-md-3 form-group">
                                                    <label class="form-label">Msorg 6</label>
                                                    <input type="text" class="form-control" name="msorg6" value="{{$plan->msorg6 ?? ''}}">
                                                </div>
                                                <div class="form-group col-4 col-md-3">
                                                    <label class="form-label">ClubKonnect</label>
                                                    <input type="text" class="form-control" name="clubkonnect" value="{{$plan->clubkonnect ?? ''}}">
                                                </div>
                                                <div class="form-group col-4 col-md-3">
                                                    <label class="form-label">Ncwallet</label>
                                                    <input type="text" class="form-control" name="ncwallet" value="{{$plan->ncwallet ?? ''}}">
                                                </div>
                                                <div class="col-4 col-md-3 form-group">
                                                    <label class="form-label">Vtpass</label>
                                                    <input type="text" name="vtpass" class="form-control" value="{{$plan->vtpass ?? ''}}">
                                                </div>

                                            </div>
                                            <div class="form-group text-end">
                                                <button class="btn-success btn w-100" type="submit">Update</button>
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
        .img-table {
            height: 40px;
        }

        .card-header {
            background-color: #fefefe;
            border-bottom: 1px solid #949d94
        }
    </style>
@endsection
