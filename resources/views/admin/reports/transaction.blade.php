@extends('admin.layouts.master')
@section('title', 'Transactions')

@section('content')
<div class="card">
    <div class="card-header d-sm-flex justify-content-between">
        <h4 class="fw-bold">All Transactions</h4>
        <form action="" method="get" id="history-search">
            <div class="input-group">
                <input type="search" name="search" value="{{request()->search}}" placeholder="Search Transactions" class="form-control" />
                <div class="input-group-prepend">
                    <button type="search" class="btn btn-info">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="row gy-3 gx-3 p-3">
        <div class="col-xl-3 col-lg-3 col-md-4">
          <div class="custom-select-box-two">
            <label>Trnx Type</label>
            <select class="form-select" onchange="window.location.href=this.value">
              <option value="{{queryBuild('type','')}}" {{request('type') == '' ? 'selected':''}}>@lang('All Type')</option>
              <option value="{{queryBuild('type','credit')}}" {{request('type') == 'credit' ? 'selected':''}}>@lang('Credit Transactions')</option>
              <option value="{{queryBuild('type','debit')}}" {{request('type') == 'debit' ? 'selected':''}}>@lang('Debit Transactions')</option>
            </select>
          </div><!-- custom-select-box-two end -->
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4">
            <div class="custom-select-box-two">
                <label>Trnx Service</label>
                <select class="form-select" onchange="window.location.href=this.value">
                  <option value="{{queryBuild('service','')}}" {{request('service') == '' ? 'selected':''}}>@lang('All Services')</option>
                  @foreach ($services as $service)
                      <option value="{{queryBuild('service',$service['service'])}}" {{request('service') == $service['service'] ? 'selected':''}}>{{ucfirst($service['service'])}}</option>
                  @endforeach
                </select>
            </div><!-- custom-select-box-two end -->
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4">
            <div class="custom-select-box-two">
                <label>Trnx Status</label>
                <select class="form-select" onchange="window.location.href=this.value">
                  <option value="{{queryBuild('status','')}}" {{request('status') == '' ? 'selected':''}}>@lang('All ')</option>
                  <option value="{{queryBuild('status','successful')}}" {{request('status') == 'successful' ? 'selected':''}}>@lang('Successful')</option>
                  <option value="{{queryBuild('status','processing')}}" {{request('status') == 'processing' ? 'selected':''}}>@lang('Processing')</option>
                  <option value="{{queryBuild('status','pending')}}" {{request('status') == 'pending' ? 'selected':''}}>@lang('Pending')</option>
                  <option value="{{queryBuild('status','reversed')}}" {{request('status') == 'reversed' ? 'selected':''}}>@lang('Reversed')</option>
                  <option value="{{queryBuild('status','failed')}}" {{request('status') == 'failed' ? 'selected':''}}>@lang('Failed')</option>
                </select>
            </div><!-- custom-select-box-two end -->
        </div>
    </div>

   <div class="card-body table-responsive">
    <table class="table table-bordered" id="datatable">
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
                <td>{{$key +1}}</td>
                <td><span class="badge bg-info">{{short_trx_type($item->service)}} </span> </td>
                <td><a href="{{route('admin.users.view', $item->user_id)}}">{{$item->user->username ?? "none"}}</a> </td>
                <td>
                    @if ($item->type == 'credit')
                        <span class="badge bg-success">credit</span>
                    @elseif ($item->type == 'debit')
                        <span class="badge bg-danger">debit</span>
                    @endif
                </td>
                <td><p>{{$item->code}} </p> {{show_datetime1($item->created_at)}} </td>
                <td>{{format_price($item->amount)}} </td>
                <td>{{format_price($item->profit)}} </td>
                <td>
                    {!! transactionStatus($item->status) !!}
                </td>
                <td><a class="btn btn-sm btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#trxDetail{{$item->id}}" >View</a></td>
                <td>
                    <div class="dropstart">
                        <button class="btn btn-light" type="button" id="" data-bs-toggle="dropdown">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu">
                            @if ($item->status == 'pending' || $item->status == 'processing')
                                <a class="dropdown-item" href="{{route('admin.transactions.approve',$item->id)}}" title="pay">Approve </a>
                                <a class="dropdown-item" href="{{route('admin.transactions.reverse',$item->id)}}" title="reverse">Reverse</a>
                                <a class="dropdown-item" href="{{route('admin.transactions.cancel',$item->id)}}" title="cancel">Cancel</a>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
   </div>
   <div class="my-2" >
       {{$transactions->links()}}
   </div>
</div>
@foreach ($transactions as $key => $item)
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
                        <p class="col-sm-6"> Start : {{show_datetime1($item->created_at)}} </p>
                        <p class="col-sm-6"> End : {{show_datetime2($item->updated_at)}} </p>
                        <p class="col-sm-6"> Status : {!!transactionStatus($item->status)!!}</p>
                        <p class="col-6"> <b>New Bal:</b> {{format_price($item->new_balance)}} </p>
                        <p class="col-6"> <b>Old Bal :</b> {{format_price($item->old_balance)}} </p>
                        <p class="col-6"> Amount : {{format_price($item->amount)}} </p>
                        <p class="col-6"> Charge : {{format_price($item->charge)}} </p>
                        <p class="col-6">Type :
                            @if ($item->type == 'credit')
                                <span class="badge bg-success">credit</span>
                            @elseif ($item->type == 'debit')
                                <span class="badge bg-danger">debit</span>
                            @endif
                        </p>
                        <p class="col-6">System :</b> {!! ($item->system) !!} </p>
                        <p class="col-6">Service : <span class="badge bg-info">{{short_trx_type($item->service)}} </span> </p>
                        <p class="col-sm-12"> Title : {{$item->title}} </p>
                        <p class="col-sm-12"> Details : {{$item->message}} </p>
                        <p class="col-sm-12"> API Response :
                            <div style="max-height: 250px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;" class="pt-2">
                                <pre>{{ json_encode($item->response, JSON_PRETTY_PRINT) }}</pre>
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
    .img-table{ height:40px ;}
    .card-header{background-color: #fefefe; border-bottom:1px solid #949d94 }
</style>
@endsection
