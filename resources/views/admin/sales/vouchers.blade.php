@extends('admin.layouts.master')
@section('title', 'Recharge Pins')

@section('content')
<div class="card">
   <div class="card-body table-responsive">
    <table class="table table-bordered" id="datatable">
        <thead>
            <tr>
              <th>#</th>
              <th>User</th>
              <th>Network</th>
              <th>Amount</th>
              <th>Quantity</th>
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
              <td>{{$item->network->name ?? "None"}}</td>
              <td>{{format_price($item->amount)}}</td>
              <td>{{($item->quantity)}} </td>
              <td>{{$item->code}}</td>
              <td>{{show_datetime($item->created_at)}}</td>
              <td>
                @if($item->status == 1)
                    <span class="badge bg-success">success</span>
                @elseif ($item->status == 2)
                    <span class="badge bg-warning">pending</span>
                @elseif ($item->status == 3)
                    <span class="badge bg-danger">declined</span>
                @endif
              </td>
              <td><a class="btn btn-sm btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#trxDetail{{$item->id}}" >View</a></td>
            </tr>
            @endforeach
          </tbody>
    </table>
    <div class="my-2">
      {{$trx->links()}}
    </div>
   </div>
</div>

@foreach ($trx as $key => $item)
    <div class="modal fade" id="trxDetail{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header py-0">
                    <h4 class="modal-title" id="myModalLabel"> Transaction Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <p> Transaction ID : {{$item->code}} </p>
                        <p class="col-sm-6"> Data : {{show_datetime1($item->created_at)}} </p>
                        <p class="col-sm-6"> End : {{show_datetime2($item->updated_at)}} </p>
                        <p class="col-6"> <b>New Bal:</b> {{format_price($item->new_balance)}} </p>
                        <p class="col-6"> <b>Old Bal :</b> {{format_price($item->old_balance)}} </p>
                        <p class="col-6"> Amount : {{format_price($item->amount)}} </p>
                        <p class="col-6"> Charge : {{format_price($item->charge)}} </p>

                        <p class="col-6">PIN :</b> {!! ($item->pins) !!} </p>
                        <p class="col-6">Serial : {!! ($item->serial) !!} </p>

                        <p class="col-sm-12"> Details : {{$item->message}} </p>
                        <p class="col-sm-12"> API Response :
                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;" class="pt-2">
                                <pre>{{ json_encode(json_decode($item->response), JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
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
