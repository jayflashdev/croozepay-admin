@extends('admin.layouts.master')
@section('title', 'Features Activation')

@section('content')
<div class="row">
    <div class="col-6 col-md-6 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">Verify Email</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'verify_email')" @if(sys_setting('verify_email') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-6 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">Referral Email</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'referral_email')" @if(sys_setting('referral_email') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">Welcome Email</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'welcome_email')" @if(sys_setting('welcome_email') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">Transaction Email</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'trx_email')" @if(sys_setting('trx_email') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
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
    .card-header{background-color: #fefefe; border-bottom:1px solid #949d94 }
</style>
@endsection
