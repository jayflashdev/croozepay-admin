@extends('admin.layouts.master')
@section('title', 'User Details')

@section('content')

<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body text-center border-bottom">
                <h5 class="card-title mt-3">{{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <p class="clearfix">
                    <span class="float-start">Username</span>
                    <span class="float-end font-weight-bold"><a href="#">{{ $user->username }}</a></span>
                </p>
                <p class="clearfix">
                    <span class="float-start">Email</span>
                    <span class="float-end text-muted">{{ $user->email }}</span>
                </p>
                <p class="clearfix">
                    <span class="float-start">Phone</span>
                    <span class="float-end text-muted">{{ $user->phone ?: 'Not available'}}</span>
                </p>

                <p class="clearfix">
                    <span class="float-start">Balance</span>
                    <span class="float-end text-muted">{{ format_price($user->balance) }}</span>
                </p>
                <p class="clearfix">
                    <span class="float-start">Refer By</span>
                    <span class="float-end text-muted">{{isset($user->refer->username) ? $user->refer->username : "None"}}</span>
                </p>
                <p class="clearfix">
                    <span class="float-start">Email Verified</span>
                    <span class="float-end text-muted">{{isset($user->email_verified_at) ? "Verified" : "None"}}</span>
                </p>
                <p class="clearfix">
                    <span class="float-start">Joined</span>
                    <span class="float-end text-muted">{{show_datetime($user->created_at)}}</span>
                </p>

                <p class="clearfix">
                    <span class="float-start">Account Type</span>
                    <span class="float-end text-muted"><span class="badge bg-primary"> {{($user->type)}} </span></span>
                </p>
                <p class="clearfix">
                    <span class="float-start">Status</span>
                    <span class="float-end text-muted">
                        @if($user->suspend == 0)
                        <span class="badge badge-pill bg-success">Active</span> @else
                        <span class="badge badge-pill bg-danger">Banned</span>
                        @endif
                    </span>
                </p>
                <p>
                    @if ($user->suspend != 1)
                        <a class="d-block btn btn-info confirmBtn" data-question="Do you want to ban this user?"
                            data-action="{{ route('admin.users.ban', [$user->id, 1]) }}" href="javascript:void(0)"> <i class="fas fa-ban"></i> Ban User </a>
                    @else
                        <a class="d-block btn btn-info confirmBtn" data-question="Do you want to unban this user?"
                            data-action="{{ route('admin.users.ban', [$user->id, 0]) }}" href="javascript:void(0)"> <i class="fas fa-check"></i> Unban User </a>
                    @endif
                </p>
                <p class="flex-grow-1 flex-shrink-0">
                    <a class="d-block btn btn-info" href="{{ route('admin.users.login', $user->id) }}" target="_blank">
                        <i class="fas fa-sign-in-alt"></i> Login as User </a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <div class="row p-4">
                    <div class="col-lg-4 col-sm-6">
                        <div class="card outline-info">
                            <div class="card-body">
                                <div class="media align-items-center">
                                <div class="media-body text-start">
                                    <h4 class="mb-0 text-info">{{ format_price($user->bonus) }}</h4>
                                    <span class="text-info">Referral Bonus</span>
                                </div>
                                <div class="align-self-center icon-lg">
                                    <i class="fas fa-users text-info"></i>
                                </div>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.referrals', $user->id) }}" class=" btn btn-block btn-info">View Referrals
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="card outline-dark">
                            <div class="card-body">
                                <div class="media align-items-center">
                                <div class="media-body text-start">
                                    <h4 class="mb-0 text-dark">{{ format_price($user->deposits->sum('amount')) }}</h4>
                                    <span class="text-dark">Total Deposits</span>
                                </div>
                                <div class="align-self-center icon-lg">
                                    <i class="fas fa-money-bill text-dark"></i>
                                </div>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.deposits', $user->id) }}" class="btn btn-block btn-dark">View All
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="card outline-primary">
                            <div class="card-body">
                                <div class="media align-items-center">
                                <div class="media-body text-start">
                                    <h4 class="mb-0 text-warning">{{ $user->transactions->count()}}</h4>
                                    <span class="text-warning">Total Transactions</span>
                                </div>
                                <div class="align-self-center icon-lg d-none d-md-block">
                                    <i class="fa fa-exchange-alt text-warning"></i>
                                </div>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.transactions', $user->id) }}" class="btn-block btn btn-warning">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="card outline-info">
                            <div class="card-body">
                                <div class="media align-items-center">
                                <div class="media-body text-start">
                                    <span class="text-info">Balance</span>
                                    <h4 class="mb-0 text-info">{{ format_price($user->balance) }}</h4>
                                </div>
                                </div>
                            </div>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#fundwallet" class=" btn btn-block btn-info">Manage Balance
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="card outline-danger">
                            <div class="card-body">
                                <div class="media align-items-center">
                                <div class="media-body text-start">
                                    <span class="text-danger">Debit</span>
                                    <h4 class="mb-0 text-danger">{{ format_price($user->transactions->where('type', 'debit')->where('status', 'successful')->sum('amount')) }}</h4>
                                </div>
                                </div>
                            </div>
                            <a href="#" class=" btn btn-block btn-danger">Debit TRX
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="card outline-success">
                            <div class="card-body">
                                <div class="media align-items-center">
                                <div class="media-body text-start">
                                     <span class="text-success">Credit</span>
                                    <h4 class="mb-0 text-success">{{ format_price($user->transactions->where('type','credit')->where('status', 'successful')->sum('amount')) }}</h4>
                                </div>
                                {{-- <div class="align-self-center icon-lg">
                                    <i class="fas fa-hand-holding-usd text-success"></i>
                                </div> --}}
                                </div>
                            </div>
                            <a href="#" class=" btn btn-block btn-success">Credit TRX
                            </a>
                        </div>
                    </div>
                </div>
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="row">
                    @csrf
                    <div class="col-12">
                        <div class="form-group col-xl-3 col-md-6 col-12">
                            <label class="form-label" for="ev">Email Verification </label>
                            <br>
                            <label class="jdv-switch jdv-switch-success m-0" for="ev">
                                <input type="checkbox" class="toggle-switch" name="email_verify" @if($user->email_verify) checked @endif id="ev" value="1">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label" for="name">@lang('Name')</label>
                        <input type="text" placeholder="@lang('Name')" id="name" name="name" class="form-control" value="{{$user->name}}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label" for="email">@lang('Email Address')</label>
                        <input type="text" placeholder="@lang('Email Address')" id="email" name="email" class="form-control" value="{{$user->email}}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label" >@lang('Username')</label>
                        <input type="text" placeholder="@lang('Username')" name="username" class="form-control" value="{{$user->username}}">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label" >@lang('Phone')</label>
                        <input type="text" placeholder="@lang('Phone')" name="phone" class="form-control" value="{{$user->phone}}">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label" >Address</label>
                        <input type="text" placeholder="Address" name="address" class="form-control" value="{{$user->address}}">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label" >Account Type</label>
                        <select class="form-select form-control" name="type">
                            <option value="user" @if($user->type == 'user') selected @endif>User</option>
                            <option value="reseller" @if($user->type == 'reseller') selected @endif>Reseller</option>
                        </select>
                    </div>
                    <hr>
                    <p class="col-12 fw-bold">Password and Pin (Leave empty if you don't want to change)</p>
                    <div class="form-group col-md-6">
                        <label class="form-label" for="password">@lang('Password')</label>
                        <input type="password" placeholder="@lang('Password')" id="password" name="password" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-label" >Transaction Pin</label>
                        <input type="text" placeholder="Trx Pin" name="trxpin" class="form-control">
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-block btn-primary w-100">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Modal --}}
<div id="fundwallet" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><strong><i class="ti ti-wallet"></i> Fund Wallet</strong></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>

            </div>
            <div class="modal-body">
                <span class="mt-0 mb-2 fw-bold">User Balance : {{format_price($user->balance)}}</span>
                <form method="POST" action="{{route('admin.users.balance', $user->id)}}" enctype="multipart/form-data" >
                   @csrf
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <div class="form-group row">
                        <label class="form-label col-sm-3">Select Option</label>
                        <div class="col-sm-9">
                            <select class="form-select form-control" name="type">
                                <option value="1">Credit</option>
                                <option value="0">Debit</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 form-label">Amount </label>
                        <div class="col-sm-9">
                            <input type="number" name="amount" step="0.01" class="form-control" placeholder="Amount" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Message </label>
                        <input type="text" name="message" class="form-control" placeholder="Write Message to user.." required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="w-100 btn btn-primary">Update</button>
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
