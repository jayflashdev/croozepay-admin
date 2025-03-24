@extends('admin.layouts.master')
@section('title', "Add Cable Plan")

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('Add Cable Plan') </h5>
            <a class="btn btn-primary btn-sm" href="{{route('admin.plan.cable')}}"> @lang('Back') </a>
        </div>
    </div>
   <div class="card-body">
    <form action="{{route('admin.plan.cable.create')}}" class="row" method="post">
        @csrf
        <div class="col-md-4 col-6 form-group">
            <label class="form-label">Plan Name</label>
            <input type="text"class="form-control" name="name" placeholder="Plan Name" value="{{old('name')}}" >
        </div>
        <div class="col-md-4 col-6 form-group">
            <label class="form-label">Cable</label>
            <select class="form-select js-select2" name="decoder_id">
                @foreach ($cables as $item)
                <option value="{{$item->id}}"> {{$item->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-6 form-group">
            <label class="form-label">Status</label>
            <select class="form-select " name="status">
                <option value="0">Choose</option>
                <option value="1" selected> Active</option>
                <option value="2"> Disabled</option>
            </select>
        </div>

        <div class="mb-3 col-6 col-md-4">
            <label class="form-label">Price</label>
            <input type="text" integer class="form-control" required name="price">
        </div>
        <div class="mb-3 col-6 col-md-4">
            <label class="form-label">Reseller Price</label>
            <input type="text" integer class="form-control" required name="reseller">
        </div>
        <div class="mb-3 col-6 col-md-4">
            <label class="form-label">Api Price</label>
            <input type="text" integer class="form-control" required name="api">
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
            <label class="form-label">Adex 6</label>
            <input type="text" class="form-control" name="adex5" placeholder="adex5" value="{{$plan->adex5 ?? ''}}">
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Adex 5</label>
            <input type="text" class="form-control" name="adex6" placeholder="adex6" value="{{$plan->adex6 ?? ''}}">
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
            <label class="form-label">Easyaccess</label>
            <input type="text" class="form-control" name="easyaccess" value="{{$plan->easyaccess ?? ''}}">
        </div>
        <div class="form-group col-4 col-md-2">
            <label class="form-label">ClubKonnect</label>
            <input type="text" class="form-control" name="clubkonnect" value="{{$plan->clubkonnect ?? ''}}">
        </div>
        <div class="form-group col-4 col-md-2">
            <label class="form-label">Ncwallet</label>
            <input type="text" class="form-control" name="ncwallet" value="{{$plan->ncwallet ?? ''}}">
        </div>
        <div class="col-4 col-md-2 form-group">
            <label class="form-label">Vtpass</label>
            <input type="text" name="vtpass" class="form-control" value="{{$plan->vtpass ?? ''}}">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success btn-block">Create</button>
        </div>
    </form>
   </div>
</div>

@endsection
