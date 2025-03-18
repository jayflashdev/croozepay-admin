@extends('admin.layouts.master')
@section('title', 'Power Payments')

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered" id="datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Customer</th>
                        <th>TRX Code</th>
                        <th>Token</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trx as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><a href="{{ route('admin.users.view', $item->user_id) }}">{{ $item->user->username ?? 'none' }}</a> </td>
                            <td>{{ format_price($item->amount) }}</td>
                            <td>{{ $item->number }}
                                <br>{{ $item->meta->customer_name ?? '' }} <br>
                                {{ $item->meta->disco ?? '' }}
                            </td>
                            <td>{{ $item->code }} <br>
                                {{ show_datetime1($item->created_at) }}
                            </td>
                            <td>{{ $item->token }}</td>
                            <td>
                                {!! transactionStatus($item->status) !!}
                            </td>

                            <td><a class="btn btn-sm btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#trxDetail{{ $item->id }}">View</a></td>
                        </tr>
                        <div class="modal fade" id="trxDetail{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header py-0">
                                        <h4 class="modal-title" id="myModalLabel"> Transaction Details</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <p class="col-sm-12"> Price : {{format_price($item->profit)}} </p>
                                            <p class="col-sm-6"> Name : {{$item->api_name}} </p>
                                            <p class="col-sm-6"> System : {{$item->system}} </p>
                                            <p class="col-sm-12"> Details : {{$item->message}} </p>
                                            <p class="col-sm-12"> API Response : {{json_encode($item->response)}} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
            <div class="my-2">
                {{ $trx->links() }}
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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Sales</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
@endsection
