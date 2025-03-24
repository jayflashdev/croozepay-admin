@extends('admin.layouts.master')
@section('title', 'Create Role')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">@lang('Create Role') </h5>
                <a class="btn btn-primary btn-sm" href="{{ route('admin.roles.index') }}"><i class="fas fa-arrow-left"></i>
                    @lang('Back')</a>
            </div>
        </div>
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="card-body row justify-content-center">
                <div class="col-lg-9">
                    <div class="form-group">
                        <label class="form-label" for="name">@lang('Name') *</label>
                        <input type="text" name="name" class="form-control" id="name"
                            placeholder="@lang('Name')" value="{{ old('name') }}">
                    </div>

                    <label class="form-label">@lang('Permissions') </label>
                    <div class="row">
                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="bills"
                                id="bills">
                            <label for="bills">Bill Service</label>
                        </div>
                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="users"
                                id="users">
                            <label for="users">Users</label>
                        </div>
                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="payments"
                                id="payments">
                            <label for="payments">Payments</label>
                        </div>
                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="transactions"
                                id="transactions">
                            <label for="transactions">Transactions</label>
                        </div>
                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="support"
                                id="support">
                            <label for="support">Support</label>
                        </div>
                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="sales"
                                id="sales">
                            <label for="sales">Sales</label>
                        </div>
                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="email_settings"
                                id="email_settings">
                            <label for="email_settings">Email Settings</label>
                        </div>
                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="payment_settings"
                                id="payment_settings">
                            <label for="payment_settings">Payment Settings</label>
                        </div>
                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="settings"
                                id="general_settings">
                            <label for="general_settings">General Settings</label>
                        </div>
                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="staffs"
                                id="staffs">
                            <label for="staffs">Staffs</label>
                        </div>
                    </div>


                    <div class="form-group text-end ">
                        <button type="submit" class="btn btn-primary w-100">@lang('Save')</button>
                    </div>
                </div>

            </div>
        </form>
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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Staffs</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
@endsection
