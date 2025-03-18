@extends('admin.layouts.master')
@section('title', 'Bulksms Transactions')

@section('content')
<div class="card">
   <div class="card-body table-responsive">
    <table class="table table-bordered" id="datatable">
        <thead>
            <tr>
                <th>S/N</th>
                <th>User</th>
                <th>Reference</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Details</th>
                <th>Numbers</th>
                <th>Balance</th>
                <th>Date</th>
                <th>Response</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($trx as $key => $item)
              <tr>
                <td>{{$key + 1}}</td>
                <td><a href="{{route('admin.users.view', $item->user_id)}}">{{$item->user->username ?? "none"}}</a> </td>
                <td>{{$item->code}}</td>
                <td>
                  @if($item->status == 1)
                      <span class="badge bg-success">successful</span>
                  @elseif ($item->status == 2)
                      <span class="badge bg-warning">pending</span>
                  @elseif ($item->status == 3)
                      <span class="badge bg-danger">cancelled</span>
                  @endif
                </td>
                <td>{{format_price($item->amount)}}</td>
                <td>
                    <p class="mb-0">Sender: {{$item->sender}}</p>
                    <p class="mb-0">Message: {{$item->message}}</p>
                </td>
                <td>
                    <p class="mb-0">Number: {{($item->number)}}</p>
                    <p class="mb-0">Total:{{$item->total_number}}</p>
                    <p class="mb-0"> Total Real:{{$item->total_real_number}}</p>
                    <p class="mb-0">Total Wrong:{{$item->total_wrong_number}}</p>

                </td>
                <td>
                    <p class="col-6"> <b>New Bal:</b> {{format_price($item->new_balance)}} </p>
                    <p class="col-6"> <b>Old Bal :</b> {{format_price($item->old_balance)}} </p>
                </td>
                <td>{{show_datetime($item->created_at)}}</td>
                <td>
                    <a class="btn btn-primary btn-sm btn-circle"  data-bs-toggle="modal" data-bs-target="#TrxResponse{{$item->id}}" title="@lang('Response') ">
                    <i class="fa fa-eye"></i></a>
                </td>
              </tr>
              <div class="modal fade" id="TrxResponse{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h6 class="modal-title" id="myModalLabel"> Transaction Response</h6>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                      </div>
                      <div class="modal-body row">
                        <p class="mb-0"> Real:{{$item->real_number}}</p>
                        <p class="mb-0"> Wrong:{{$item->wrong_number}}</p>
                        <p>API : {{$item->api_name}}</p>
                        <p>API Ref: {{$item->api_reference}}</p>
                        <p class="col-12">{{$item->response}} </p>
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
