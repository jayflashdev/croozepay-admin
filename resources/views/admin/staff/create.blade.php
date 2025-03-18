@extends('admin.layouts.master')
@section('title', 'Create Staff')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('Create New Staff') </h5>
            <a class="btn btn-primary btn-sm" href="{{route('admin.staffs.index')}}"><i class="fas fa-arrow-left"></i> @lang('Back')</a>
        </div>
    </div>
    <form action="{{ route('admin.staffs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body"> 
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="name">{{__('Name')}}</label>
                        <input type="text" placeholder="{{__('Name')}}" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">{{__('Email Address')}}</label>
                        <input type="text" placeholder="{{__('Email Address')}}" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mobile">{{__('Phone')}}</label>
                        <input type="text" placeholder="{{__('Phone')}}" id="mobile" name="mobile" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="name">{{__('Username')}}</label>
                        <input type="text" placeholder="{{__('Userame')}}" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">{{__('Password')}}</label>
                        <input type="password" placeholder="{{__('Password')}}" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="name">{{__('Role')}}</label>
                        <select name="role_id" required class="form-select">
                            @foreach($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group text-end mx-3">
                <button type="submit" class="btn btn-sm btn-primary w-100">{{__('Create Staff')}}</button>
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