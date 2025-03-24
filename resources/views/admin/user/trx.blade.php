@extends('admin.layouts.master')
@section('title')
    {{ ucfirst($user->username) }} Transactions
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover" id="datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>TRX Code</th>
                        <th>Amount</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trx as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                @if ($item->type == 'credit')
                                    <span class="badge bg-success">credit</span>
                                @elseif ($item->type == 'debit')
                                    <span class="badge bg-danger">debit</span>
                                @endif
                            </td>
                            <td>{{ $item->code }} </td>
                            <td>{{ format_price($item->amount) }} </td>
                            <td> <span class="badge bg-primary">{{ $item->service }}</span></td>
                            <td> {{ show_datetime($item->created_at) }}</td>
                            <td>
                               {!! transactionStatus($item->status)!!}
                            </td>
                            <td><a class="btn btn-sm btn-info" href="#" data-bs-toggle="modal" data-bs-target="#trxDetail{{ $item->id }}">View</a></td>
                        </tr>
                        <div class="modal fade" id="trxDetail{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header py-0">
                                        <h4 class="modal-title" id="myModalLabel"> Transaction Details</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <p> Transaction ID : {{ $item->code }} </p>
                                            <p class="col-sm-6"> Date : {{ ($item->created_at) }} </p>
                                            <p class="col-sm-6"> Status : {!! transactionStatus($item->status)!!}
                                            </p>
                                            <p class="col-6"> Amount : {{ format_price($item->amount) }} </p>
                                            <p class="col-6"> Charge : {{ format_price($item->charge) }} </p>
                                            <p class="col-6">Type : {!! trans_type($item->type) !!} </p>
                                            <p class="col-6">Service : <span class="badge bg-info">{{ short_trx_type($item->service) }} </span> </p>
                                            <p class="col-sm-12"> Details : {{ $item->message }} </p>
                                            <p class="col-sm-12"> API Response : {{json_encode($item->response) }} </p>
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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
@endsection
