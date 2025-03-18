@extends('admin.layouts.master')
@section('title', 'Edit Staff')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Edit {{$staff->name}}</h5>
            <a class="btn btn-primary btn-sm" href="{{route('admin.staffs.index')}}"><i class="fas fa-arrow-left"></i> @lang('Back')</a>
        </div>
    </div>
    <form action="{{ route('admin.staffs.update',$staff->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body row">                    
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="name">{{__('Name')}}</label>
                    <input type="text" value="{{ $staff->user->name }}" placeholder="{{__('Name')}}" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">{{__('Email Address')}}</label>
                    <input type="text" value="{{ $staff->user->email }}" placeholder="{{__('Email Address')}}" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="mobile">{{__('Phone')}}</label>
                    <input type="text" value="{{ $staff->user->phone }}" placeholder="{{__('Phone')}}" id="mobile" name="mobile" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="password">{{__('Password')}}</label>
                    <input type="password" placeholder="{{__('Password')}}" id="password" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label" for="name">{{__('Role')}}</label>
                    <select name="role_id" required class="form-select">
                        @foreach($roles as $role)
                            <option value="{{$role->id}}" @php if($staff->role_id == $role->id) echo "selected"; @endphp >{{$role->name}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>
            <div class="form-group text-end mx-3">
                <button type="submit" class="btn btn-sm btn-primary w-100">{{__('Update Staff')}}</button>
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