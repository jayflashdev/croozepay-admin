@extends('admin.layouts.master')
@section('title', 'Payment Settings')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="my-2">
            <p class="fw-bold mb-0">Payvessel Webhook</p>
            <span class="form-control">{{route('payvessel.webhook')}}</span>
        </div>
        <div class="my-2">
            <p class="fw-bold mb-0">Monnify Webhook</p>
            <span class="form-control">{{route('monnify.webhook')}}</span>
        </div>
        <div class="my-2">
            <p class="fw-bold mb-0">Billstack Webhook</p>
            <span class="form-control">{{route('billstack.webhook')}}</span>
        </div>
    </div>
</div>
<div class="row">
     <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold ">Paystack Payment</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-9">
                        <label class="form-label">Enable Paystack</label>
                    </div>
                    <div class="col-md-3">
                        <label class="jdv-switch jdv-switch-success mb-0">
                            <input type="checkbox" onchange="updateSystem(this, 'paystack_payment')" @if(sys_setting('paystack_payment') == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold ">Flutterwave Payment</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-9">
                        <label class="form-label">Enable Fluttterwave</label>
                    </div>
                    <div class="col-md-3">
                        <label class="jdv-switch jdv-switch-success mb-0">
                            <input type="checkbox" onchange="updateSystem(this, 'flutter_payment')" @if(sys_setting('flutter_payment') == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold ">Bank Transfer</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-9">
                        <label class="form-label">Enable Transfer</label>
                    </div>
                    <div class="col-md-3">
                        <label class="jdv-switch jdv-switch-success mb-0">
                            <input type="checkbox" onchange="updateSystem(this, 'bank_transfer')" @if(sys_setting('bank_transfer') == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold ">Payvessel Accounts</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-9">
                        <label class="form-label">Enable Bank Accounts</label>
                    </div>
                    <div class="col-md-3">
                        <label class="jdv-switch jdv-switch-success mb-0">
                            <input type="checkbox" onchange="updateSystem(this, 'payvessel_accounts')" @if(sys_setting('payvessel_accounts') == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold ">Billstack</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-9">
                        <label class="form-label">Enable Billstack</label>
                    </div>
                    <div class="col-md-3">
                        <label class="jdv-switch jdv-switch-success mb-0">
                            <input type="checkbox" onchange="updateSystem(this, 'billstack_accounts')" @if(sys_setting('billstack_accounts') == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold ">Auto Transfer</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-9">
                        <label class="form-label">Enable Auto Bank</label>
                    </div>
                    <div class="col-md-3">
                        <label class="jdv-switch jdv-switch-success mb-0">
                            <input type="checkbox" onchange="updateSystem(this, 'auto_bank')" @if(sys_setting('auto_bank') == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold ">Monnify Activation</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-9">
                        <label class="form-label">Enable Monnify</label>
                    </div>
                    <div class="col-md-3">
                        <label class="jdv-switch jdv-switch-success mb-0">
                            <input type="checkbox" onchange="updateSystem(this, 'monnify_payment')" @if(sys_setting('monnify_payment') == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-md-9">
                        <label class="form-label">Demo Mode</label>
                    </div>
                    <div class="col-md-3">
                        <label class="jdv-switch jdv-switch-success mb-0">
                            <input type="checkbox" onchange="updateSystem(this, 'monnify_demo')" @if(sys_setting('monnify_demo') == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold ">Billstack Credentials</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('admin.setting.env_key') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="paystack">
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="BILLSTACK_SECRET">
                        <label class="form-label">{{__('SECRET KEY')}}</label>
                        <input type="text" class="form-control" name="BILLSTACK_SECRET" value="{{  env('BILLSTACK_SECRET') }}" placeholder="SECRET KEY" required>
                    </div>
                    <div class="form-group mb-0 text-end">
                        <button type="submit" class="btn btn-sm w-100 w-100 btn-primary">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold ">Paystack Credentials</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('admin.setting.env_key') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="paystack">
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="PAYSTACK_PUBLIC_KEY">
                        <label class="form-label">{{__('PUBLIC KEY')}}</label>
                        <input type="text" class="form-control" name="PAYSTACK_PUBLIC_KEY" value="{{  env('PAYSTACK_PUBLIC_KEY') }}" placeholder="PUBLIC KEY" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="PAYSTACK_SECRET_KEY">
                        <label class="form-label">{{__('SECRET KEY')}}</label>
                        <input type="text" class="form-control" name="PAYSTACK_SECRET_KEY" value="{{  env('PAYSTACK_SECRET_KEY') }}" placeholder="SECRET KEY" required>
                    </div>
                    <div class="form-group mb-0 text-end">
                        <button type="submit" class="btn btn-sm w-100 w-100 btn-primary">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold ">Flutter Credentials</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('admin.setting.env_key') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="flutter">
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="FLW_PUBLIC_KEY">
                        <label class="form-label">{{__('FLW PUBLIC KEY')}}</label>
                        <input type="text" class="form-control" name="FLW_PUBLIC_KEY" value="{{  env('FLW_PUBLIC_KEY') }}" placeholder="FLUTTERWAVE PUBLIC KEY" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="FLW_SECRET_KEY">
                        <label class="form-label">{{__('FLW SECRET KEY')}}</label>
                        <input type="text" class="form-control" name="FLW_SECRET_KEY" value="{{  env('FLW_SECRET_KEY') }}" placeholder="FLUTTERWAVE PUBLIC KEY" required>
                    </div>
                    <div class="form-group mb-0 text-end">
                        <button type="submit" class="btn btn-sm w-100 w-100 btn-primary">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold ">Payvessel Credentials</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('admin.setting.env_key') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="paystack">
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="PAYVESSEL_PUBLIC_KEY">
                        <label class="form-label">{{__('PAYVESSEL PUBLIC KEY')}}</label>
                        <input type="text" class="form-control" name="PAYVESSEL_PUBLIC_KEY" value="{{  env('PAYVESSEL_PUBLIC_KEY') }}" placeholder="PUBLIC KEY" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="PAYVESSEL_SECRET_KEY">
                        <label class="form-label">{{__('PAYVESSEL SECRET KEY')}}</label>
                        <input type="text" class="form-control" name="PAYVESSEL_SECRET_KEY" value="{{  env('PAYVESSEL_SECRET_KEY') }}" placeholder="SECRET KEY" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="PAYVESSEL_ID">
                        <label class="form-label">{{__('PAYVESSEL BUSINESS ID')}}</label>
                        <input type="text" class="form-control" name="PAYVESSEL_ID" value="{{  env('PAYVESSEL_ID') }}" placeholder="Business ID" required>
                    </div>
                    <div class="form-group mb-0 text-end">
                        <button type="submit" class="btn btn-sm w-100 w-100 btn-primary">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold ">Monnify Credentials</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('admin.setting.env_key') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="monnify">
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MONNIFY_API_KEY">
                        <label class="form-label">Monnify Api Key</label>
                        <input type="text" class="form-control" name="MONNIFY_API_KEY" value="{{  env('MONNIFY_API_KEY') }}" placeholder="Monnify Api key" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MONNIFY_CONTRACT">
                        <label class="form-label">Monnify Contract</label>
                        <input type="text" class="form-control" name="MONNIFY_CONTRACT" value="{{  env('MONNIFY_CONTRACT') }}" placeholder="Monnify Contract Code" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MONNIFY_SECRET_KEY">
                        <label class="form-label">Monnify Secret</label>
                        <input type="text" class="form-control" name="MONNIFY_SECRET_KEY" value="{{  env('MONNIFY_SECRET_KEY') }}" placeholder="Monnify secret key" required>
                    </div>
                    <div class="form-group mb-0 text-end">
                        <button type="submit" class="btn btn-sm w-100 btn-primary">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <h5 class="card-header fw-bold mb-0">Bank Payment Details</h5>
            <div class="card-body">
                <form action="{{route('admin.setting.store_settings')}}" method="post" class="row">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="bank_name">
                        <label class="form-label">Bank Name</label>
                        <input type="text"class="form-control" name="bank_name" placeholder="Bank Name" value="{{sys_setting('bank_name')}}" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="account_name">
                        <label class="form-label">Account Name</label>
                        <input type="text"class="form-control" name="account_name" placeholder="Account Name" value="{{sys_setting('account_name')}}" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="account_number">
                        <label class="form-label">Account Number</label>
                        <input type="text"class="form-control" name="account_number" placeholder="Account Number" value="{{sys_setting('account_number')}}" required>
                    </div>
                    <div class="form-group text-end mb-0">
                        <button class="btn w-100 btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4">
        <div class="card">
            <h5 class="card-header fw-bold mb-0">Payment Charges</h5>
            <div class="card-body">
                <form action="{{route('admin.setting.store_settings')}}" method="post" class="row">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="bank_fee">
                        <label class="form-label">Manual Transfer Charges</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="bank_fee" placeholder="Bank Charges" value="{{sys_setting('bank_fee')}}" required>
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-info text-white">{{get_setting('currency')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="auto_fee2">
                        <label class="form-label">9PSB Charges</label>
                        <div class="input-group">
                            <input type="text"class="form-control" name="auto_fee2" placeholder="9PSB Transfer Charges" value="{{sys_setting('auto_fee2')}}" required>
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-info text-white">#</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="auto_fee">
                        <label class="form-label">Auto Transfer Charges</label>
                        <div class="input-group">
                            <input type="text"class="form-control" name="auto_fee" placeholder="Transfer Charges" value="{{sys_setting('auto_fee')}}" required>
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-info text-white">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="auto_cap">
                        <label class="form-label">Auto Transfer Capped @</label>
                        <div class="input-group">
                            <input type="text"class="form-control" name="auto_cap" placeholder="Autobank Capped" value="{{sys_setting('auto_cap')}}" required>
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-info text-white">{{get_setting('currency')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="card_fee">
                        <label class="form-label">Card Payment (%)</label>
                        <div class="input-group">
                            <input type="text"class="form-control" name="card_fee" placeholder="Card Charges" value="{{sys_setting('card_fee')}}" required>
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-info text-white">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <button class="btn btn-success w-100" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header h4">Currency Settings</div>
    <div class="card-body">
        <form class="row" action="{{route('admin.setting.update')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group col-sm-6 ">
                <label class="form-label">Currency Symbol</label>
                <input type="text" class="form-control" name="currency" value="{{get_setting('currency')}}" required placeholder="Currency Symbol"/>
            </div>
            <div class="form-group col-sm-6">
                <label class="form-label">Currency Code</label>
                <input type="text" class="form-control" name="currency_code" value="{{get_setting('currency_code')}}" required placeholder="Currency Code"/>
            </div>
            <div class="text-end">
                <button class="btn btn-success btn-block" type="submit">@lang('Update Setting')</button>
            </div>
        </form>
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

@section('scripts')
<script>
    function updateSystem(el, name){
        if($(el).is(':checked')){
            var value = 1;
        }
        else{
            var value = 0;
        }
        $.post('{{ route('admin.setting.sys_settings') }}', {_token:'{{ csrf_token() }}', name:name, value:value}, function(data){
            if(data == '1'){
                Snackbar.show({
                    text: '{{__('Settings Updated Successfully')}}',
                    pos: 'top-right',
                    backgroundColor: '#38c172'
                });
            }
            else{
                Snackbar.show({
                    text: '{{__('Something went wrong')}}',
                    pos: 'top-right',
                    backgroundColor: '#e3342f'
                });
            }
        });
    }
</script>
@endsection
@section('styles')
<style>
    .primage {
        max-height: 50px !important;
        max-width: 150px !important;
        margin: 0;
    }
    .card-header{border-top:1px solid #1d1f1d }
</style>
@endsection
