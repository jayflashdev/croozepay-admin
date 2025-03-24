@extends('admin.layouts.master')
@section('title', 'Add Datacard Plan')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">@lang('Add Datacard Plan') </h5>
                <a class="btn btn-primary btn-sm" href="{{ route('admin.plan.datacard') }}"> @lang('Back') </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.plan.datacard.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="mb-3 col-6 col-md-3">
                        <label class="form-label">Network</label>
                        <select class="form-select wide mb-3" name="network_id" id="network">
                            @foreach ($networks as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-6 col-md-3">
                        <label class="form-label">Network Type</label>
                        <select class="form-select wide mb-3" name="type" id="type">
                            <option value="GIFTING">GIFTING</option>
                            <option value="SME">SME</option>
                            <option value="CG">CG</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Plan Name</label>
                        <input type="text" required class="form-control" name="name">
                    </div>
                    <div class=" col-6 col-md-3">
                        <label class="form-label">Data Size</label>
                        <select id="inputState" class="form-select wide" name="size">
                            <option value="MB">MB</option>
                            <option value="GB">GB</option>
                            <option value="TB">TB</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Days</label>
                        <select id="inputState" class="form-select wide" name="day">
                            <option value="1 Day">1 Day</option>
                            <option value="3 Days">3 Days</option>
                            <option value="7 Days">7 Days</option>
                            <option value="1 Month">1 Month</option>
                            <option value="3 Months">3 Months</option>
                            <option value="7 Months">7 Months</option>
                            <option value="9 Months">9 Months</option>
                            <option value="1 Yrs">1 year</option>
                            <option value="2 Yrs">2 years</option>
                            <option value="3 yrs">3 years</option>
                        </select>
                    </div>
                    {{-- <div class="col-6 col-md-3 form-group">
                        <label class="form-label">Status</label>
                        <select class="form-select " name="status">
                            <option value="0">Choose</option>
                            <option value="1" selected> Active</option>
                            <option value="2"> Disabled</option>
                        </select>
                    </div> --}}

                    <h6 class="fw-bold">API Price</h6>
                    <div class="mb-3 col-6 col-md-4">
                        <label class="form-label">Price</label>
                        <input type="text" integer class="form-control" required name="price" title="Please enter a valid number">
                    </div>
                    <div class="mb-3 col-6 col-md-4">
                        <label class="form-label">Agent Price</label>
                        <input type="text" integer class="form-control" required name="reseller" title="Please enter a valid number">
                    </div>
                    <div class="mb-3 col-6 col-md-4">
                        <label class="form-label">Api Price</label>
                        <input type="text" integer class="form-control" required name="api" title="Please enter a valid number">
                    </div>
                </div>
                <div class="row">
                    <h6 class="fw-bold">API Codes</h6>

                    <div class="col-md-2 form-group">
                        <label class="form-label">Adex 1</label>
                        <input type="text"class="form-control" name="adex1" placeholder="adex1">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Adex 2</label>
                        <input type="text"class="form-control" name="adex2" placeholder="adex2">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Adex 3</label>
                        <input type="text"class="form-control" name="adex3" placeholder="adex3">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Adex 4</label>
                        <input type="text"class="form-control" name="adex4" placeholder="adex4">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Adex 6</label>
                        <input type="text"class="form-control" name="adex5" placeholder="adex5">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Adex 5</label>
                        <input type="text"class="form-control" name="adex6" placeholder="adex6">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Msorg 1</label>
                        <input type="text"class="form-control" name="msorg1">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Msorg 2</label>
                        <input type="text"class="form-control" name="msorg2">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Msorg 3</label>
                        <input type="text"class="form-control" name="msorg3">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Msorg 4</label>
                        <input type="text"class="form-control" name="msorg4">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Msorg 5</label>
                        <input type="text"class="form-control" name="msorg5">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Msorg 6</label>
                        <input type="text"class="form-control" name="msorg6">
                    </div>
                    <div class="mb-3 col-md-2">
                        <label class="form-label">Easyaccess</label>
                        <input type="text" class="form-control" name="easyaccess">
                    </div>
                    {{-- <div class="mb-3 col-md-2">
                        <label class="form-label">ClubKonnect</label>
                        <input type="text" class="form-control" name="clubkonnect">
                    </div> --}}
                    <div class="mb-3 col-md-2">
                        <label class="form-label">Ncwallet</label>
                        <input type="text" class="form-control" name="ncwallet">
                    </div>
                    {{-- <div class="col-md-2">
                        <label class="form-label">Vtpass</label>
                        <input type="text" name="vtpass" class="form-control">
                    </div> --}}
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-block">Create Plan</button>
                </div>
            </form>
        </div>
    </div>

@endsection
