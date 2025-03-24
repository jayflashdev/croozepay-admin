@extends('admin.layouts.master')
@section('title', 'Deposits')

@section('content')
<div class="card">
   <div class="card-body table-responsive">
    <table class="table table-bordered" id="datatable">
        <thead>
            <tr>
                <th>#</th>
                <th>TRX ID</th>
                <th>User</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Details</th>
                <th>Status</th>
                <th>Date</th>
                <th>Response</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($deposits as $key => $item)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$item->trx}}</td>
                <td><a href="{{route('admin.users.view', $item->user_id)}}">{{$item->user->username ?? ""}}</a> </td>
                <td>{{format_price($item->amount)}}</td>
                <td><span class="badge bg-info">{{$item->gateway}}</span></td>
                <td>{{$item->message}}</td>
                <td>
                    @if($item->status == 1)
                        <span class="badge bg-success">@lang('Complete')</span>
                    @elseif ($item->status == 2)
                        <span class="badge bg-warning">@lang('Pending')</span>
                    @elseif ($item->status == 3)
                        <span class="badge bg-danger">@lang('Rejected')</span>
                    @endif
                </td>
                <td>{{show_datetime($item->created_at)}}</td>
                <td>
                    <a class="btn btn-sm btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#Response{{$item->id}}" >View</a>
                </td>
            </tr>
            <div class="modal fade" id="Response{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel"> Payment Response</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                           <p>{{$item->response}}</p>
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
    <div class="my-2" >
        {{$deposits->links()}}
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
