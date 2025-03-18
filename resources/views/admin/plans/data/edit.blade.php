@extends('admin.layouts.master')
@section('title', "Edit Data Plan")

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('Edit') {{$plan->network->name}} {{$plan->name }}</h5>
            <a class="btn btn-primary btn-sm" href="{{route('admin.plan.data')}}"> @lang('Back') </a>
        </div>
    </div>
   <div class="card-body">
    <form action="" class="row" method="post">
        @csrf
        <div class="form-group col-md-4">
            <label class="form-label">Network</label>
            <select class="form-control form-select form-group" name="network_id" id="network">
                @foreach ($networks as $item)
                <option value="{{$item->id}}" @if($plan->network_id == $item->id) selected @endif>{{$item->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label class="form-label">Network Type</label>
            <select class="form-control form-select form-group" name="type" id="type">
                <option value="GIFTING"@if($plan->type == "GIFTING") selected @endif>GIFTING</option>
                <option value="CG"@if($plan->type == "CG") selected @endif>CG</option>
                <option value="SME"@if($plan->type == "SME") selected @endif>SME</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Data Name</label>
            <input  type="text" class="form-control" name="name" value="{{($plan->name)}}" >
        </div>
        <div class=" col-md-4">
            <label class="form-label">Data Size</label>
            <select id="inputState" class="form-control form-select" name="size">
                <option value="MB"@if($plan->size == "MB") selected @endif>MB</option>
                <option value="GB"@if($plan->size == "GB") selected @endif>GB</option>
                <option value="TB"@if($plan->size == "TB") selected @endif>TB</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Days</label>
            <select id="inputState" class="form-control form-select" name="day">
                <option value="1 Day"@if($plan->day == "1 Day") selected @endif>1 Day</option>
                <option value="2 Days" @if($plan->day == "2 Days") selected @endif>2 Days</option>
                <option value="3 Days" @if($plan->day == "3 Days") selected @endif>3 Days</option>
                <option value="5 Days" @if($plan->day == "5 Days") selected @endif>5 Days</option>
                <option value="7 Days" @if($plan->day == "7 Days") selected @endif>7 Days</option>
                <option value="14 Days" @if($plan->day == "14 Days") selected @endif>14 Days</option>

                <option value="Sat" @if($plan->day == "Sat") selected @endif>Sat.</option>
                <option value="Sun" @if($plan->day == "Sun") selected @endif>Sun.</option>
                <option value="Sat & Sun" @if($plan->day == "Sat & Sun") selected @endif>Sat. & Sun.</option>

                <option value="Night" @if($plan->day == "Night") selected @endif>Night</option>

                <option value="1 Month" @if($plan->day == "1 Month") selected @endif>1 Month</option>
                <option value="2 Months" @if($plan->day == "2 Months") selected @endif>2 Months</option>
                <option value="3 Months" @if($plan->day == "3 Months") selected @endif>3 Months</option>
                <option value="4 Months" @if($plan->day == "4 Months") selected @endif>4 Months</option>
                <option value="6 Months" @if($plan->day == "6 Months") selected @endif >6 Months</option>
                <option value="7 Months" @if($plan->day == "7 Months") selected @endif >7 Months</option>
                <option value="9 Months" @if($plan->day == "9 Months") selected @endif>9 Months</option>
                <option value="1 Yrs" @if($plan->day == "1 Yrs") selected @endif>1 year</option>
                <option value="2 Yrs"@if($plan->day == "2 Yrs") selected @endif>2 years</option>
                <option value="3 yrs"@if($plan->day == "3 Yrs") selected @endif>3 years</option>
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label class="form-label">Status</label>
            <select class="form-select " name="status">
                <option value="0">Choose</option>
                <option value="1" @if($plan->status  == 1) selected @endif> Active</option>
                <option value="2" @if($plan->status != 1) selected @endif> Disabled</option>
            </select>
        </div>
        <div class="row">
            <div class="form-group col-6 col-md-4">
                <label class="form-label">Price</label>
                <input type="text" integer class="form-control" required name="price" value="{{($plan->price)}}" >
            </div>
            <div class="form-group col-6 col-md-4">
                <label class="form-label">Reseller Price</label>
                <input type="text" integer class="form-control" required name="reseller" value="{{($plan->reseller)}}" >
            </div>
            <div class="form-group col-6 col-md-4">
                <label class="form-label">Api Price</label>
                <input type="text" integer class="form-control" required name="api" value="{{($plan->api)}}" >
            </div>
        </div>
        <div class="col-md-2 form-group">
            <label class="form-label">Adex 1</label>
            <input type="text" class="form-control" name="adex1" placeholder="adex1" value="{{$plan->adex1 ?? ''}}">
        </div>
        <div class="col-md-2 form-group">
            <label class="form-label">Adex 2</label>
            <input type="text" class="form-control" name="adex2" placeholder="adex2" value="{{$plan->adex2 ?? ''}}">
        </div>
        <div class="col-md-2 form-group">
            <label class="form-label">Adex 3</label>
            <input type="text" class="form-control" name="adex3" placeholder="adex3" value="{{$plan->adex3 ?? ''}}">
        </div>
        <div class="col-md-2 form-group">
            <label class="form-label">Adex 4</label>
            <input type="text" class="form-control" name="adex4" placeholder="adex4" value="{{$plan->adex4 ?? ''}}">
        </div>
        <div class="col-md-2 form-group">
            <label class="form-label">Adex 6</label>
            <input type="text" class="form-control" name="adex5" placeholder="adex5" value="{{$plan->adex5 ?? ''}}">
        </div>
        <div class="col-md-2 form-group">
            <label class="form-label">Adex 5</label>
            <input type="text" class="form-control" name="adex6" placeholder="adex6" value="{{$plan->adex6 ?? ''}}">
        </div>
        <div class="col-md-2 form-group">
            <label class="form-label">Msorg 1</label>
            <input type="text" class="form-control" name="msorg1" value="{{$plan->msorg1 ?? ''}}">
        </div>
        <div class="col-md-2 form-group">
            <label class="form-label">Msorg 2</label>
            <input type="text" class="form-control" name="msorg2" value="{{$plan->msorg2 ?? ''}}">
        </div>
        <div class="col-md-2 form-group">
            <label class="form-label">Msorg 3</label>
            <input type="text" class="form-control" name="msorg3" value="{{$plan->msorg3 ?? ''}}">
        </div>
        <div class="col-md-2 form-group">
            <label class="form-label">Msorg 4</label>
            <input type="text" class="form-control" name="msorg4" value="{{$plan->msorg4 ?? ''}}">
        </div>
        <div class="col-md-2 form-group">
            <label class="form-label">Msorg 5</label>
            <input type="text" class="form-control" name="msorg5" value="{{$plan->msorg5 ?? ''}}">
        </div>
        <div class="col-md-2 form-group">
            <label class="form-label">Msorg 6</label>
            <input type="text" class="form-control" name="msorg6" value="{{$plan->msorg6 ?? ''}}">
        </div>
        <div class="form-group col-md-2">
            <label class="form-label">Easyaccess</label>
            <input type="text" class="form-control" name="easyaccess" value="{{$plan->easyaccess ?? ''}}">
        </div>
        <div class="form-group col-md-2">
            <label class="form-label">ClubKonnect</label>
            <input type="text" class="form-control" name="clubkonnect" value="{{$plan->clubkonnect ?? ''}}">
        </div>
        <div class="form-group col-md-2">
            <label class="form-label">Ncwallet</label>
            <input type="text" class="form-control" name="ncwallet" value="{{$plan->ncwallet ?? ''}}">
        </div>
        <div class="col-md-2">
            <label class="form-label">Vtpass</label>
            <input type="text" name="vtpass" class="form-control" value="{{$plan->vtpass ?? ''}}">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success btn-block">Update</button>
        </div>
    </form>
   </div>
</div>

@endsection
