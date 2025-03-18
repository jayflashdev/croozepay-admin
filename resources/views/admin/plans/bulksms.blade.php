@extends('admin.layouts.master')
@section('title', 'Bulk SMS')
@section('content')
<div class="col-md-12">
    <div class="card">
        <h5 class="card-header fw-bold">Bulksms Price</h5>
        <div class="card-body">
            <form action="{{route('admin.setting.store_settings')}}" method="post" class="row">
                @csrf
                <div class="form-group col-md-4">
                    <input type="hidden" name="types[]" value="bulksms_price">
                    <label class="form-label">User Price</label>
                    <input type="text" class="form-control" value="{{sys_setting('bulksms_price')}}" name="bulksms_price">
                </div>
                <div class="form-group col-md-4">
                    <input type="hidden" name="types[]" value="bulksms_reseller">
                    <label class="form-label">Reseller Price</label>
                    <input type="text" class="form-control" value="{{sys_setting('bulksms_reseller')}}" name="bulksms_reseller">
                </div>
                <div class="form-group col-md-4">
                    <input type="hidden" name="types[]" value="bulksms_api">
                    <label class="form-label">API Price</label>
                    <input type="text" class="form-control" value="{{sys_setting('bulksms_api')}}" name="bulksms_api">
                </div>
                <div class="form-group text-end">
                    <button class="btn btn-success w-100" type="submit">Save</button>
                </div>
            </form>
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
