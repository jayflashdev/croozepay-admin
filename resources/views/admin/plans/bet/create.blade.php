@extends('admin.layouts.master')
@section('title', "Add Bet Plan")

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">@lang('Add Bet Plan') </h5>
            <a class="btn btn-primary btn-sm" href="{{route('admin.plan.bet')}}"> @lang('Back') </a>
        </div>
    </div>
   <div class="card-body">
    <form action="{{route('admin.plan.bet.create')}}" class="row" method="post" enctype="multipart/form-data">
        @csrf
        <div class="col-md-4 col-6 form-group">
            <label class="form-label">Bet Name</label>
            <input type="text"class="form-control" name="name" placeholder="Bet Name" value="{{old('name')}}" >
        </div>
        <div class="col-md-4 col-6 form-group">
            <label class="form-label">Image</label>
            <input type="file" name="image" accept="image/*" id="imgInp" class="form-control" >
        </div>
        <div class="form-group col-md-4 col-6">
            <label class="form-label">Charges/Fee</label>
            <input type="number" class="form-control" name="fee" placeholder="Plan Charges" required>
        </div>
        <div class="col-md-4 col-6 form-group">
            <label class="form-label">Ncwallet</label>
            <input type="text"class="form-control" name="ncwallet" placeholder="ncwallet" >
        </div>
        <div class="col-md-4 col-6 form-group">
            <label class="form-label">ClubKonnect</label>
            <input type="text"class="form-control" name="clubkonnect" placeholder="clubkonnect" >
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success btn-block">Create</button>
        </div>
    </form>
   </div>
</div>

@endsection
