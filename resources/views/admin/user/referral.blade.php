@extends('admin.layouts.master')
@section('title', 'User Referrals')

@section('content')
<div class="card">
    <h5 class="card-header">{{$user->username}} Referrals</h5>
    <div class="card-body table-responsive">
        <table class="table table-bordered" id="datatable">
            <thead>
                <tr>         
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone </th>
                    <th>Date Joined</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($referrals as $key => $item)
                <tr>
                    <td>{{$key +1}}</td>
                    <td>{{$item->username}}</td>
                    <td>{{$item->email}}</td>
                    <td>{{$item->phone}}</td>
                    <td>{{ date('F d, Y H:m:s', strtotime($item->created_at))}}</td>
                    <td>{{format_price($item->balance)}}</td>
                </tr>
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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@endsection