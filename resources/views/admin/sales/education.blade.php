@extends('admin.layouts.master')
@section('title', 'Education Sales')

@section('content')
<div class="card">
   <div class="card-body table-responsive">
    <table class="table table-bordered" id="datatable">
        <thead>
            <tr>
              <th>#</th>
              <th>User</th>
              <th>Exam</th>
              <th>Quantuty</th>
              <th>PIN</th>
              <th>Amount</th>
              <th>Balance</th>
              <th>TRX Code</th>
              <th>Date</th>
              <th>Status</th>
              <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($trx as $key => $item)
            <tr>
                <td>{{$key + 1}}</td>
                <td><a href="{{route('admin.users.view', $item->user_id)}}">{{$item->user->username ?? "none"}}</a> </td>
                <td>{{($item->education->name)}} </td>
                <td>{{$item->quantity ?? "1"}}</td>
                <td>{{$item->pins}}</td>
                <td>{{format_price($item->amount)}}</td>
                <td>
                    <p>Bal: {{format_price($item->new_balance)}} </p>
                    <p>Bal :{{format_price($item->old_balance)}} </p>
                </td>
                <td>{{$item->code}}</td>
                <td>{{show_datetime($item->created_at)}}</td>
                <td>
                  @if($item->status == 1)
                      <span class="badge bg-success">successful</span>
                  @elseif ($item->status == 2)
                      <span class="badge bg-warning">pending</span>
                  @elseif ($item->status == 3)
                      <span class="badge bg-danger">cancelled</span>
                  @endif
                </td>

                <td><a class="btn btn-sm btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#trxDetail{{$item->id}}" >View</a></td>
            </tr>
            <div class="modal fade" id="trxDetail{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header py-0">
                            <h4 class="modal-title" id="myModalLabel"> Transaction Details</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <p class="col-sm-12"> Details : {{$item->name}} </p>
                                <p class="col-sm-12"> API Response : {{$item->response}} </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
    <div class="my-2">
      {{$trx->links()}}
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
