@extends('admin.layouts.master')
@section('title', 'Users Settings')

@section('content')
<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <h5 class="card-header">Affiliate Setting</h5>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'is_affiliate')" @if(sys_setting('is_affiliate') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="card">
            <h5 class="card-header">API Access</h5>
            <div class="card-body text-center">
                <label class="jdv-switch jdv-switch-success mb-0">
                    <input type="checkbox" onchange="updateSystem(this, 'developer_access')" @if(sys_setting('developer_access') == 1) checked @endif >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <h5 class="card-header">Affiliate Commission</h5>
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.setting.store_settings')}}" method="post" class="row">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="referral_commission">
                        <div class="input-group">
                            <input type="text" class="form-control" name="referral_commission" placeholder="Referral Commission" value="{{sys_setting('referral_commission')}}" required>
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

    <div class="col-sm-6 col-md-4">
        <h5 class="card-header">Reseller Upgrade</h5>
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.setting.store_settings')}}" method="post" class="row">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="reseller_upgrade">
                        <div class="input-group">
                            <input type="text" class="form-control" name="reseller_upgrade" placeholder="Reseller Upgrade" value="{{sys_setting('reseller_upgrade')}}" required>
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-info text-white">{{get_setting('currency')}}</span>
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
    .card-header{background-color: #fefefe; border-bottom:1px solid #949d94 }
</style>
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
