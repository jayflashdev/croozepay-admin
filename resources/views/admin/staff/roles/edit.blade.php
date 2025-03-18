@extends('admin.layouts.master')
@section('title', 'Edit Role')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Edit {{$role->name}} Permissions </h5>
            <a class="btn btn-primary btn-sm" href="{{route('admin.roles.index')}}"><i class="fas fa-arrow-left"></i> @lang('Back')</a>
        </div>
    </div>
    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        <div class="card-body row justify-content-center">
            <div class="col-lg-9">
                <div class="form-group">
                    <label class="form-label" for="name">@lang('Name') *</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="@lang('Name') " value="{{ $role->name }}" >
                </div>
                @php
                    $permissions = json_decode($role->permissions);
                @endphp
                <label class="form-label">@lang('Permissions') </label>
                <div class="row">
                    <div class="col-md-4 col-sm-6 my-3">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="1"  @if(in_array(1, $permissions)) checked @endif > @lang('Bills')
                    </div>
                    <div class="col-md-4 col-sm-6 my-3">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="2"  @if(in_array(2, $permissions)) checked @endif id=""> @lang('Users')
                    </div>

                    <div class="col-md-4 col-sm-6 my-3">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="3"  @if(in_array(3, $permissions)) checked @endif id=""> @lang(' Payments')
                    </div>
                    <div class="col-md-4 my-3">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="4"  @if(in_array(4, $permissions)) checked @endif id=""> @lang('Transactions')
                    </div>

                    <div class="col-md-4 col-sm-6 my-3">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="5"  @if(in_array(5, $permissions)) checked @endif id=""> @lang('Support')
                    </div>

                    <div class="col-md-4 col-sm-6 my-3">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="6" @if(in_array(6, $permissions)) checked @endif id=""> @lang('Email Settings')
                    </div>
                    <div class="col-md-4 col-sm-6 my-3">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="7" @if(in_array(7, $permissions)) checked @endif id=""> @lang('Payment Setting')
                    </div>
                    <div class="col-md-4 col-sm-6 my-3">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="8" @if(in_array(8, $permissions)) checked @endif id=""> @lang('Settings')
                    </div>
                    <div class="col-md-4 col-sm-6 my-3">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="9" @if(in_array(9, $permissions)) checked @endif id=""> @lang('Staffs') 
                    </div>
                    <div class="col-md-4 col-sm-6 my-3">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="10" @if(in_array(10, $permissions)) checked @endif id="">Update
                    </div>
                </div>

                <div class="form-group text-end ">
                    <button type="submit" class="btn btn-primary ">@lang('Save')</button>
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