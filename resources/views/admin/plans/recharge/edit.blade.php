@extends('admin.layouts.master')
@section('title', "Edit Plan")

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('Edit') Recharge Pin</h5>
            <a class="btn btn-primary btn-sm" href="{{route('admin.plan.recharge')}}"> @lang('Back') </a>
        </div>
    </div>
   <div class="card-body">
    <form action="" class="row" method="post">
        @csrf
        <div class="form-group col-4">
            <label class="form-label">Network</label>
            @if ($plan->network)
            <input type="text" value="{{$plan->network->name ?? "Null"}}" readonly class="form-control">
            @else
            <select class="form-select wide mb-3" name="network_id" id="network">
                @foreach ($networks as $item)
                <option value="{{$item->id}}" @if($plan->network_id == $item->id) selected @endif>{{$item->name}}</option>
                @endforeach
            </select>
            @endif
        </div>
        <div class="col-4 form-group">
            <label class="form-label">Amount</label>
            <select id="inputState" class="form-select wide" name="value">
                <option @if($plan->value == "100") selected @endif value="100">100</option>
                <option @if($plan->value == "200") selected @endif value="200">200</option>
                <option @if($plan->value == "500") selected @endif value="500">500</option>
                <option @if($plan->value == "1000") selected @endif value="1000">1000</option>
            </select>
        </div>
        <div class="col-4 form-group">
            <label class="form-label">Status</label>
            <select class="form-select " name="status">
                <option value="0">Choose</option>
                <option value="1" @if($plan->status == 1) selected @endif> Enabled</option>
                <option value="0" @if($plan->status != 1) selected @endif> Disabled</option>
            </select>
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Adex 1</label>
            <input type="text" class="form-control" name="adex1" placeholder="adex1" value="{{$plan->adex1 ?? ''}}">
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Adex 2</label>
            <input type="text" class="form-control" name="adex2" placeholder="adex2" value="{{$plan->adex2 ?? ''}}">
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Adex 3</label>
            <input type="text" class="form-control" name="adex3" placeholder="adex3" value="{{$plan->adex3 ?? ''}}">
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Adex 4</label>
            <input type="text" class="form-control" name="adex4" placeholder="adex4" value="{{$plan->adex4 ?? ''}}">
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Adex 5</label>
            <input type="text" class="form-control" name="adex6" placeholder="adex6" value="{{$plan->adex6 ?? ''}}">
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Adex 6</label>
            <input type="text" class="form-control" name="adex5" placeholder="adex5" value="{{$plan->adex5 ?? ''}}">
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Msorg 1</label>
            <input type="text" class="form-control" name="msorg1" value="{{$plan->msorg1 ?? ''}}">
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Msorg 2</label>
            <input type="text" class="form-control" name="msorg2" value="{{$plan->msorg2 ?? ''}}">
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Msorg 3</label>
            <input type="text" class="form-control" name="msorg3" value="{{$plan->msorg3 ?? ''}}">
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Msorg 4</label>
            <input type="text" class="form-control" name="msorg4" value="{{$plan->msorg4 ?? ''}}">
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Msorg 5</label>
            <input type="text" class="form-control" name="msorg5" value="{{$plan->msorg5 ?? ''}}">
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Msorg 6</label>
            <input type="text" class="form-control" name="msorg6" value="{{$plan->msorg6 ?? ''}}">
        </div>
        <div class="form-group col-4 col-md-2">
            <label class="form-label">Clubkonnect</label>
            <input type="text" class="form-control" name="clubkonnect" value="{{$plan->clubkonnect ?? ''}}">
        </div>
        <div class="form-group col-4 col-md-2">
            <label class="form-label">Ncwallet</label>
            <input type="text" class="form-control" name="ncwallet" value="{{$plan->ncwallet ?? ''}}">
        </div>
        {{-- <div class="form-group col-4 col-md-2">
            <label class="form-label">Vtpass</label>
            <input type="text" name="vtpass" class="form-control" value="{{$plan->vtpass ?? ''}}">
        </div> --}}

        <div class="form-group">
            <button type="submit" class="btn btn-success btn-block">Update</button>
        </div>
    </form>
   </div>
</div>

@endsection
