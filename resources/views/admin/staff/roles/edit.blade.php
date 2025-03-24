@extends('admin.layouts.master')
@section('title', 'Edit Role')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Edit {{ $role->name }} Permissions </h5>
                <a class="btn btn-primary btn-sm" href="{{ route('admin.roles.index') }}"><i class="fas fa-arrow-left"></i>
                    @lang('Back')</a>
            </div>
        </div>
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf
            <div class="card-body row justify-content-center">
                <div class="col-lg-9">
                    <div class="form-group">
                        <label class="form-label" for="name">@lang('Name') *</label>
                        <input type="text" name="name" class="form-control" id="name"
                            placeholder="@lang('Name') " value="{{ $role->name }}">
                    </div>
                    @php
                        $permissions = json_decode($role->permissions);
                    @endphp
                    <label class="form-label">@lang('Permissions') </label>
                    <div class="row">
                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="bills"
                                id="bills" @if (in_array('bills', $permissions)) checked @endif>
                            <label for="bills">@lang('Bill Service')</label>
                        </div>

                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="users"
                                id="users" @if (in_array('users', $permissions)) checked @endif>
                            <label for="users">@lang('Users')</label>
                        </div>

                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="payments"
                                id="payments" @if (in_array('payments', $permissions)) checked @endif>
                            <label for="payments">@lang('Payments')</label>
                        </div>

                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="transactions"
                                id="transactions" @if (in_array('transactions', $permissions)) checked @endif>
                            <label for="transactions">@lang('Transactions')</label>
                        </div>

                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="support"
                                id="support" @if (in_array('support', $permissions)) checked @endif>
                            <label for="support">@lang('Support')</label>
                        </div>

                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="sales"
                                id="sales" @if (in_array('sales', $permissions)) checked @endif>
                            <label for="sales">@lang('Sales')</label>
                        </div>

                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="email_settings"
                                id="email_settings" @if (in_array('email_settings', $permissions)) checked @endif>
                            <label for="email_settings">@lang('Email Settings')</label>
                        </div>

                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="payment_settings"
                                id="payment_settings" @if (in_array('payment_settings', $permissions)) checked @endif>
                            <label for="payment_settings">@lang('Payment Settings')</label>
                        </div>

                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="settings"
                                id="settings" @if (in_array('settings', $permissions)) checked @endif>
                            <label for="settings">@lang('General Settings')</label>
                        </div>

                        <div class="col-md-4 col-sm-6 my-3">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="staffs"
                                id="staffs" @if (in_array('staffs', $permissions)) checked @endif>
                            <label for="staffs">@lang('Staffs')</label>
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
