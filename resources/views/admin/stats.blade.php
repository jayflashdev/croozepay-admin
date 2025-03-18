@extends('admin.layouts.master')
@section('title', 'Admin Dashboard')

@section('content')

<div class="row">
    <div class="col-sm-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">System Balance</h4>
                <h4 class="mt-3 mb-2"><b>{{ format_price($datas['balance']) }}</b></h4>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Referral Balance</h4>
                <h4 class="mt-3 mb-2"><b>{{ format_price($datas['bonus']) }}</b></h4>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Credit Transactions</h4>
                <h4 class="mt-3 mb-2 text-success"><b>{{ format_price($datas['c_trx']) }}</b></h4>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Debit Transactions</h4>
                <h4 class="mt-3 mb-2 text-danger"><b>{{ format_price($datas['d_trx']) }}</b></h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="card-title text-muted">Total Users</h4>
                <h4 class="mt-3 mb-2"><b>{{ $datas['u_count'] }}</b></h4>
                <p class="text-muted mb-0 mt-3">
                    <b>{{ App\Models\User::whereDate('created_at', today())->where('blocked', 0)->where('user_role', 'user')->count() }}</b> Today
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Manual Deposits</h4>
                <h4 class="mt-3 mb-2"><b>{{ format_price($mpayment) }}</b></h4>
                <p class="text-muted mb-0 mt-3">
                    <b>{{ format_price(App\Models\Mdeposit::whereDate('updated_at', today())->where('status', 1)->sum('amount')) }}</b> Today
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Total Deposits</h4>
                <h4 class="mt-3 mb-2"><b>{{ format_price($deposit) }}</b></h4>
                <p class="text-muted mb-0 mt-3">
                    <b>{{ format_price(App\Models\Deposit::whereDate('updated_at', today())->where('status', 1)->sum('amount')) }}</b> Today
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card text-center">
            <div class="card-body p-t-10">
                <h4 class="card-title text-muted mb-0">Pending Deposits</h4>
                <h4 class="mt-3 mb-2"><b>{{ format_price($mpayment2) }}</b></h4>
                <p class="text-muted mb-0 mt-3">
                    <b>{{ format_price(App\Models\Mdeposit::whereDate('updated_at', today())->where('status', 2)->sum('amount')) }}</b> Today
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <h5 class="fw-bold">Bills Transactions</h5>
@php
        $services = [
            'airtime' => 'Airtime Trxs',
            'betting' => 'Betting Trxs',
            'bulksms' => 'Bulksms Trxs',
            'cable' => 'Cable Trxs',
            'data' => 'Data Trxs',
            'datacard' => 'Datacard Trxs',
            'deposit' => 'Deposit Trxs',
            'exam' => 'Exam Trxs',
            'power' => 'Power Trxs',
            'rechargecard' => 'Rechargecard Trxs',
            'referral' => 'Referral Trxs',
            'system' => 'System Trxs',
        ];
    @endphp

    @foreach ($services as $type => $label)
        <div class="col-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body p-t-10">
                    <h4 class="card-title text-muted mb-0">{{ $label }}</h4>
                    <h5 class="mt-3 mb-2"><b>{{ format_price($transactions->where('service', $type)->where('status', 'successful')->sum('amount')) }}</b></h5>
                    <p class="text-muted mb-0 mt-3">
                        <b>{{ format_price(App\Models\Transaction::whereDate('updated_at', today())->where('service', $type)->where('status', 'successful')->sum('amount')) }}</b> Today
                    </p>
                </div>
            </div>
        </div>
    @endforeach

    {{-- @foreach ([1 => 'Airtime Trxs', 2 => 'Data Trxs', 3 => 'Airtime Swap'] as $type => $label)
        <div class="col-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body p-t-10">
                    <h4 class="card-title text-muted mb-0">{{ $label }}</h4>
                    <h5 class="mt-3 mb-2"><b>{{ format_price($networkTrx->where('type', $type)->sum('amount')) }}</b></h5>
                    <p class="text-muted mb-0 mt-3">
                        <b>{{ format_price(App\Models\NetworkTrx::whereDate('updated_at', today())->where('type', $type)->where('status', 1)->sum('amount')) }}</b> Today
                    </p>
                </div>
            </div>
        </div>
    @endforeach --}}
{{--
    @foreach (['DecoderTrx' => 'Cable Trx', 'EduTrx' => 'Exam PINS', 'RechargePin' => 'Recharge Cards', 'PowerTrx' => 'Electricity Trx', 'DataPin' => 'Data Cards'] as $model => $label)
        <div class="col-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body p-t-10">
                    <h4 class="card-title text-muted mb-0">{{ $label }}</h4>
                    <h5 class="mt-3 mb-2"><b>{{ format_price("App\Models\\$model"::where('status', 1)->sum('amount')) }}</b></h5>
                    <p class="text-muted mb-0 mt-3">
                        <b>{{ format_price("App\Models\\$model"::whereDate('updated_at', today())->where('status', 1)->sum('amount')) }}</b> Today
                    </p>
                </div>
            </div>
        </div>
    @endforeach --}}

</div>
<div class="row">
        @php
            $swaptrx = App\Models\NetworkTrx::whereType(3)->whereStatus(2)->orderByDesc('id')->limit(10)->get();
            $userz = App\Models\User::whereBlocked(0)->whereUserRole('user')->orderByDesc('id')->limit(20)->get();
            $mpayments = App\Models\Mdeposit::orderByDesc('id')->limit(20)->get();
        @endphp
    <div class="col-lg-6" hidden>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5>Airtime Swaps</h5>
                    <a href="{{route('admin.reports.swap')}}" class="btn btn-primary btn-sm">View All</a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="datatable">
                    <thead>
                        <tr>
                          <th>#</th>
                          <th>User</th>
                          <th>Number</th>
                          <th>Network</th>
                          <th>Amount</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($swaptrx as $key => $item)
                          <tr>
                            <td>{{$key + 1}}</td>
                            <td><a href="{{route('admin.users.view', $item->user_id)}}">{{$item->user->username ?? "none"}}</a> </td>
                            <td>{{($item->number)}} </td>
                            <td>{{$item->network->name ?? "Null"}}</td>
                            <td>{{format_price($item->amount)}}</td>
                            <td>
                              <div class="dropstart">
                                <button class="btn btn-light" type="button" id="" data-bs-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @if($item->status == 2)
                                    <a class="dropdown-item" href="{{route('admin.reports.swap.approve',$item->id)}}" title="pay">Approve</a>
                                    <a class="dropdown-item" href="{{route('admin.reports.swap.delete',$item->id)}}" title="delete">Reject</a>
                                    @endif
                                  </div>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5>Manual Deposits</h5>
                    <a href="{{route('admin.mdeposits')}}" class="btn btn-primary btn-sm">View All</a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="datatable1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>TRX ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($mpayments as $key => $item)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$item->code}}</td>
                            <td>{{$item->user->username}}</td>
                            <td>{{format_price($item->amount)}}</td>
                            <td>{{show_datetime($item->created_at)}}</td>
                            <td>{{$item->message}}</td>
                            <td>
                                @if($item->status == 1)
                                    <span class="badge bg-success">@lang('Complete')</span>
                                @elseif ($item->status == 2)
                                    <span class="badge bg-warning">@lang('Pending')</span>
                                @elseif ($item->status == 3)
                                    <span class="badge bg-danger">@lang('Reject')</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropstart">
                                    <button class="btn btn-light" type="button" id="" data-bs-toggle="dropdown">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @if($item->status == 2)
                                        <a class="dropdown-item" href="{{route('admin.mdeposit.pay',$item->id)}}" title="pay">Confirm</a>
                                        <a class="dropdown-item" href="{{route('admin.mdeposit.reject',$item->id)}}" title="delete">Reject</a>
                                        @endif
                                        {{-- <a class="dropdown-item" href="{{route('admin.mdeposit.delete',$item->id)}}" title="delete">Delete </a> --}}
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#manualReceipt{{$item->id}}" >View</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        {{-- Modal --}}
                        <div class="modal fade" id="manualReceipt{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel"> Payment Receipt</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="{{my_asset($item->image)}}" alt="" class="man-img">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times"></i> @lang('Close')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5>Recent Users</h5>
                    <a href="{{route('admin.users.index')}}" class="btn btn-primary btn-sm">View All</a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover table-bordered" id="datatable2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Information</th>
                            <th>Balance</th>
                            <th>User Since</th>
                            <th>Status </th>
                            <th>Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($userz as $key => $item)
                        <tr>
                            <td>{{ $key +1 }}</td>
                            <td>
                                <p>Name : {{ text_trim($item->name, 25) }} </p>
                                <p>Email : {{ $item->email }} </p>
                                <p>Username : {{ $item->username }} </p>
                            </td>
                            {{-- <td> {{isset($item->package->name) ? $item->package->name : "None"}} --}}
                            </td>
                            <td> {{format_price($item->balance) }} </td>
                            <td>{{$item->created_at->diffForHumans()}}</td>
                            <td><span class="badge @if($item->suspend == 1)bg-danger @else bg-primary @endif">@if($item->suspend == 1)banned @else active @endif </span></td>
                            <td>
                                <div class="dropstart">
                                    <button class="btn btn-light" type="button" id="" data-bs-toggle="dropdown">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{route('admin.users.view' ,$item->id )}}">@lang('Details')</a>
                                        {{-- <a class="dropdown-item" href="{{route('users.edit' ,$item->id )}}">@lang('Edit')</a> --}}
                                        @if($item->suspend != 1)
                                        <a class="dropdown-item" href="{{route('admin.users.ban' ,[$item->id, 1])}}">@lang('Ban')</a> @else
                                        <a class="dropdown-item" href="{{route('admin.users.ban' ,[$item->id, 0])}}">@lang('Unban')</a>
                                        @endif
                                        <a class="dropdown-item" href="{{route('admin.users.delete' ,$item->id )}}">@lang('Delete')</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
@section('styles')
<style>
    .man-img{ width:100%;height: auto;    }
    .card-header{background-color: #fefefe; border-bottom:1px solid #949d94 }
</style>
@endsection
