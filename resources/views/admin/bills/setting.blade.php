@extends('admin.layouts.master')
@section('title', 'Bills Settings')

@section('content')
<h5 class="text-center card-header mb-2 ">Services Activation</h5>
<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">Data</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'is_data')" @if(sys_setting('is_data') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">Airtime</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'is_airtime')" @if(sys_setting('is_airtime') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">Cable TV</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'is_cable')" @if(sys_setting('is_cable') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">Education</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'is_education')" @if(sys_setting('is_education') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
     <div class="col-sm-6 col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">DataCard</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'is_datacard')" @if(sys_setting('is_datacard') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">Electricity</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'is_electricity')" @if(sys_setting('is_electricity') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">Bulk SMS</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'is_bulksms')" @if(sys_setting('is_bulksms') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">Airtime to Cash</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'airtime_cash')" @if(sys_setting('airtime_cash') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">Airtime PIN</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'airtime_pin')" @if(sys_setting('airtime_pin') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6 text-center">Betting</h3>
            </div>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'is_betting')" @if(sys_setting('is_betting') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="card">
            <h6 class="card-header text-center">API Access</h5>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'developer_access')" @if(sys_setting('developer_access') == 1) checked @endif >
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
    .card-header{background-color: #fefefe; border-bottom:1px solid #949d94 }
</style>
@endsection
