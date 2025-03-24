@extends('admin.layouts.master')
@section('title', 'Edit Profile')
@php
    $user = Auth::user();
@endphp
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.profile') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group col-xl-3 col-md-6 col-12">
                        <label class="form-label" for="ev">Email Verification </label>
                        <br>
                        <label class="jdv-switch jdv-switch-success m-0" for="ev">
                            <input type="checkbox" class="toggle-switch" name="email_verify" @if($user->email_verify) checked @endif id="ev" value="1">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 form-label" for="name">{{__('Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{__('Name')}}" id="name" name="name" class="form-control" value="{{$user->name}}" required>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 form-label" for="name">Username</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="Username" id="name" name="username" class="form-control" value="{{$user->username}}" required>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 form-label" for="email">{{__('Email Address')}}</label>
                        <div class="col-sm-9">
                            <input type="email" placeholder="{{__('Email Address')}}" id="email" name="email" class="form-control" value="{{$user->email}}" required>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-sm-3 form-label" for="password">{{__('Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" placeholder="{{__('Password')}}" id="password" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-3 text-end">
                        <button type="submit" class="btn btn-primary w-100">{{__('Save')}}</button>
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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@endsection
