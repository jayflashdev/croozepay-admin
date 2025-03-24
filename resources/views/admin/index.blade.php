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
                <div class="card-body">
                    <h4 class="card-title text-muted">Total Users</h4>
                    <h4 class="mt-3 mb-2"><b>{{ $datas['u_count'] }}</b></h4>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body p-t-10">
                    <h4 class="card-title text-muted mb-0">Today Transactions</h4>
                    <h4 class="mt-3 mb-2"><b>{{ format_price($todayTrx) }}</b></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @php
            $userz = App\Models\User::whereBlocked(0)->whereUserRole('user')->orderByDesc('id')->limit(20)->get();
            $transactions = App\Models\Transaction::orderByDesc('id')->limit(20)->get();
        @endphp
        {{-- </div> --}}
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5>Recent transactions</h5>
                        <a href="{{ route('admin.transactions') }}" class="btn btn-primary btn-sm">View All</a>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered" id="datatable1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Service</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>TRX Code</th>
                                <th>Amount</th>
                                <th>Profit</th>
                                <th>Status</th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><span class="badge bg-info">{{ short_trx_type($item->service) }} </span> </td>
                                    <td><a
                                            href="{{ route('admin.users.view', $item->user_id) }}">{{ $item->user->username ?? 'none' }}</a>
                                    </td>
                                    <td>
                                        @if ($item->type == 'credit')
                                            <span class="badge bg-success">credit</span>
                                        @elseif ($item->type == 'debit')
                                            <span class="badge bg-danger">debit</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p>{{ $item->code }} </p> {{ show_datetime1($item->created_at) }}
                                    </td>
                                    <td>{{ format_price($item->amount) }} </td>
                                    <td>{{ format_price($item->profit) }} </td>
                                    <td>
                                        {!! transactionStatus($item->status) !!}
                                    </td>
                                    <td><a class="btn btn-sm btn-primary" href="#" data-bs-toggle="modal"
                                            data-bs-target="#trxDetail{{ $item->id }}">View</a></td>
                                    <td>
                                        <div class="dropstart">
                                            <button class="btn btn-light" type="button" id="" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                @if ($item->status == 'pending' || $item->status == 'processing')
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.transactions.approve', $item->id) }}"
                                                        title="pay">Approve </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.transactions.reverse', $item->id) }}"
                                                        title="reverse">Reverse</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.transactions.cancel', $item->id) }}"
                                                        title="cancel">Cancel</a>
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
                        <h5>Recent Users</h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">View All</a>
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
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <p>Name : {{ text_trim($item->name, 25) }} </p>
                                        <p>Email : {{ $item->email }} </p>
                                        <p>Username : {{ $item->username }} </p>
                                    </td>
                                    {{-- <td> {{isset($item->package->name) ? $item->package->name : "None"}} --}}
                                    </td>
                                    <td> {{ format_price($item->balance) }} </td>
                                    <td>{{ $item->created_at->diffForHumans() }}</td>
                                    <td><span
                                            class="badge @if ($item->suspend == 1) bg-danger @else bg-primary @endif">
                                            @if ($item->suspend == 1)
                                                banned
                                            @else
                                                active
                                            @endif
                                        </span></td>
                                    <td>
                                        <div class="dropstart">
                                            <button class="btn btn-light" type="button" id=""
                                                data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.users.view', $item->id) }}">@lang('Details')</a>
                                                {{-- <a class="dropdown-item" href="{{route('users.edit' ,$item->id )}}">@lang('Edit')</a> --}}
                                                @if ($item->suspend != 1)
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.users.ban', [$item->id, 1]) }}">@lang('Ban')</a>
                                                @else
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.users.ban', [$item->id, 0]) }}">@lang('Unban')</a>
                                                @endif
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.users.delete', $item->id) }}">@lang('Delete')</a>
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
        .man-img {
            width: 100%;
            height: auto;
        }

        .card-header {
            background-color: #fefefe;
            border-bottom: 1px solid #949d94
        }
    </style>
@endsection
