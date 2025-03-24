@extends('admin.layouts.master')
@section('title', 'API Selection')
@php
    $providers = [
        'adex1',
        'adex2',
        'adex3',
        'adex4',
        'adex5',
        'adex6',
        'msorg1',
        'msorg2',
        'msorg3',
        'msorg4',
        'msorg5',
        'msorg6',
        'ncwallet',
        'vtpass',
        'clubkonnect',
        'easyaccess',
    ];
    $providers2 = [
        'adex1',
        'adex2',
        'adex3',
        'adex4',
        'adex5',
        'adex6',
        'msorg1',
        'msorg2',
        'msorg3',
        'msorg4',
        'msorg5',
        'msorg6',
        'ncwallet',
        'vtpass',
        'clubkonnect',
    ];
    $providers3 = ['adex1', 'adex2', 'adex3', 'adex4', 'adex5', 'adex6', 'ncwallet'];
    $providers4 = [
        'adex1',
        'adex2',
        'adex3',
        'adex4',
        'adex5',
        'adex6',
        'msorg1',
        'msorg2',
        'msorg3',
        'msorg4',
        'msorg5',
        'msorg6',
        'ncwallet',
        'easyaccess',
    ];
    $providers5 = [
        'adex1',
        'adex2',
        'adex3',
        'adex4',
        'adex5',
        'adex6',
        'msorg1',
        'msorg2',
        'msorg3',
        'msorg4',
        'msorg5',
        'msorg6',
        'ncwallet',
        'clubkonnect',
    ];
@endphp
@section('content')

    <div class="card">
        <h5 class="card-header text-center mb-2">AIRTIME API</h5>
        <div class="card-body">
            <form action="{{ route('admin.setting.api_settings') }}" class="row ajaxForm" method="post">
                @csrf
                <div class="col-md-3 form-group">
                    <input type="hidden" name="types[]" value="mtn_airtime">
                    <label class="form-label">MTN Airtime</label>
                    <select class="form-select" name="mtn_airtime" required>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider }}" @if (api_setting('mtn_airtime') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <input type="hidden" name="types[]" value="glo_airtime">
                    <label class="form-label">GLO Airtime</label>
                    <select class="form-select" name="glo_airtime" required>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider }}" @if (api_setting('glo_airtime') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <input type="hidden" name="types[]" value="airtel_airtime">
                    <label class="form-label">Airtel Airtime</label>
                    <select class="form-select" name="airtel_airtime" required>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider }}" @if (api_setting('airtel_airtime') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <input type="hidden" name="types[]" value="mob_airtime">
                    <label class="form-label">9Mobile Airtime</label>
                    <select class="form-select" name="mob_airtime" required>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider }}" @if (api_setting('mob_airtime') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="w-100 btn btn-success" type="submit">Save Settings</button>
            </form>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header text-center mb-2">DATA API</h5>
        <div class="card-body">
            <form action="{{ route('admin.setting.api_settings') }}" class="row ajaxForm" method="post">
                @csrf
                <div class="col-md-3 form-group">
                    <input type="hidden" name="types[]" value="mtn_data">
                    <label class="form-label">MTN Data</label>
                    <select class="form-select" name="mtn_data" required>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider }}" @if (api_setting('mtn_data') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <input type="hidden" name="types[]" value="glo_data">
                    <label class="form-label">GLO Data</label>
                    <select class="form-select" name="glo_data" required>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider }}" @if (api_setting('glo_data') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <input type="hidden" name="types[]" value="airtel_data">
                    <label class="form-label">Airtel Data</label>
                    <select class="form-select" name="airtel_data" required>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider }}" @if (api_setting('airtel_data') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <input type="hidden" name="types[]" value="mob_data">
                    <label class="form-label">9Mobile Data</label>
                    <select class="form-select" name="mob_data" required>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider }}" @if (api_setting('mob_data') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100">Save Settings</button>
            </form>
        </div>
    </div>
    <div class="d-none">
        {{-- MTN Data --}}
        <div class="card">
            <h5 class="card-header text-center mb-2">MTN DATA API</h5>
            <div class="card-body">
                <form action="{{ route('admin.setting.api_settings') }}" class="row ajaxForm" method="post">
                    @csrf
                    <div class="col-md-4 form-group">
                        <input type="hidden" name="types[]" value="mtn_data_sme">
                        <label class="form-label">SME Data</label>
                        <select class="form-select" name="mtn_data_sme" required>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" @if (api_setting('mtn_data_sme') == $provider) selected @endif>
                                    {{ ucfirst($provider) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="hidden" name="types[]" value="mtn_data_cg">
                        <label class="form-label">CG Data</label>
                        <select class="form-select" name="mtn_data_cg" required>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" @if (api_setting('mtn_data_cg') == $provider) selected @endif>
                                    {{ ucfirst($provider) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="hidden" name="types[]" value="mtn_data_gifting">
                        <label class="form-label">Gifting Data</label>
                        <select class="form-select" name="mtn_data_gifting" required>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" @if (api_setting('mtn_data_gifting') == $provider) selected @endif>
                                    {{ ucfirst($provider) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Save Settings</button>
                </form>
            </div>
        </div>
        {{-- Glo Data --}}
        <div class="card">
            <h5 class="card-header text-center mb-2">GLO DATA API</h5>
            <div class="card-body">
                <form action="{{ route('admin.setting.api_settings') }}" class="row ajaxForm" method="post">
                    @csrf
                    <div class="col-md-4 form-group">
                        <input type="hidden" name="types[]" value="glo_data_sme">
                        <label class="form-label">SME Data</label>
                        <select class="form-select" name="glo_data_sme" required>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" @if (api_setting('glo_data_sme') == $provider) selected @endif>
                                    {{ ucfirst($provider) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="hidden" name="types[]" value="glo_data_cg">
                        <label class="form-label">CG Data</label>
                        <select class="form-select" name="glo_data_cg" required>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" @if (api_setting('glo_data_cg') == $provider) selected @endif>
                                    {{ ucfirst($provider) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="hidden" name="types[]" value="glo_data_gifting">
                        <label class="form-label">Gifting Data</label>
                        <select class="form-select" name="glo_data_gifting" required>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" @if (api_setting('glo_data_gifting') == $provider) selected @endif>
                                    {{ ucfirst($provider) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Save Settings</button>
                </form>
            </div>
        </div>
        {{-- Airtel Data --}}
        <div class="card">
            <h5 class="card-header text-center mb-2">AIRTEL DATA API</h5>
            <div class="card-body">
                <form action="{{ route('admin.setting.api_settings') }}" class="row ajaxForm" method="post">
                    @csrf
                    <div class="col-md-4 form-group">
                        <input type="hidden" name="types[]" value="airtel_data_sme">
                        <label class="form-label">SME Data</label>
                        <select class="form-select" name="airtel_data_sme" required>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" @if (api_setting('airtel_data_sme') == $provider) selected @endif>
                                    {{ ucfirst($provider) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="hidden" name="types[]" value="airtel_data_cg">
                        <label class="form-label">CG Data</label>
                        <select class="form-select" name="airtel_data_cg" required>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" @if (api_setting('airtel_data_cg') == $provider) selected @endif>
                                    {{ ucfirst($provider) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="hidden" name="types[]" value="airtel_data_gifting">
                        <label class="form-label">Gifting Data</label>
                        <select class="form-select" name="airtel_data_gifting" required>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" @if (api_setting('airtel_data_gifting') == $provider) selected @endif>
                                    {{ ucfirst($provider) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Save Settings</button>
                </form>
            </div>
        </div>
        {{-- 9 Mobile --}}
        <div class="card">
            <h5 class="card-header text-center mb-2">9MOBIlE DATA API</h5>
            <div class="card-body">
                <form action="{{ route('admin.setting.api_settings') }}" class="row ajaxForm" method="post">
                    @csrf
                    <div class="col-md-4 form-group">
                        <input type="hidden" name="types[]" value="mob_data_sme">
                        <label class="form-label">SME Data</label>
                        <select class="form-select" name="mob_data_sme" required>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" @if (api_setting('mob_data_sme') == $provider) selected @endif>
                                    {{ ucfirst($provider) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="hidden" name="types[]" value="mob_data_cg">
                        <label class="form-label">CG Data</label>
                        <select class="form-select" name="mob_data_cg" required>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" @if (api_setting('mob_data_cg') == $provider) selected @endif>
                                    {{ ucfirst($provider) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <input type="hidden" name="types[]" value="mob_data_gifting">
                        <label class="form-label">Gifting Data</label>
                        <select class="form-select" name="mob_data_gifting" required>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" @if (api_setting('mob_data_gifting') == $provider) selected @endif>
                                    {{ ucfirst($provider) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <h5 class="card-header text-center mb-2">CABLE API</h5>
        <div class="card-body">
            <form action="{{ route('admin.setting.api_settings') }}" class="row ajaxForm" method="post">
                @csrf
                <div class="col-md-4 form-group">
                    <input type="hidden" name="types[]" value="dstv_sel">
                    <label class="form-label">DSTV API</label>
                    <select class="form-select" name="dstv_sel" required>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider }}" @if (api_setting('dstv_sel') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <input type="hidden" name="types[]" value="gotv_sel">
                    <label class="form-label text-uppercase">gotv API</label>
                    <select class="form-select" name="gotv_sel" required>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider }}" @if (api_setting('gotv_sel') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <input type="hidden" name="types[]" value="startime_sel">
                    <label class="form-label text-uppercase">startime API</label>
                    <select class="form-select" name="startime_sel" required>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider }}" @if (api_setting('startime_sel') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100">Save Settings</button>
            </form>
        </div>
    </div>
    <div class="card d-none">
        <h5 class="card-header text-center mb-2">EXAM API</h5>
        <div class="card-body">
            <form action="{{ route('admin.setting.api_settings') }}" class="row ajaxForm" method="post">
                @csrf
                <div class="col-md-4 form-group">
                    <input type="hidden" name="types[]" value="waec_sel">
                    <label class="form-label text-uppercase">waec API</label>
                    <select class="form-select" name="waec_sel" required>
                        @foreach ($providers2 as $provider)
                            <option value="{{ $provider }}" @if (api_setting('waec_sel') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <input type="hidden" name="types[]" value="neco_sel">
                    <label class="form-label text-uppercase">neco API</label>
                    <select class="form-select" name="neco_sel" required>
                        @foreach ($providers2 as $provider)
                            <option value="{{ $provider }}" @if (api_setting('neco_sel') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <input type="hidden" name="types[]" value="nabteb_sel">
                    <label class="form-label text-uppercase">nabteb API</label>
                    <select class="form-select" name="nabteb_sel" required>
                        @foreach ($providers2 as $provider)
                            <option value="{{ $provider }}" @if (api_setting('nabteb_sel') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100">Save Settings</button>
            </form>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header text-center mb-2">Others</h5>
        <div class="card-body">
            <form action="{{ route('admin.setting.api_settings') }}" class="row ajaxForm" method="post">
                @csrf
                <div class="col-md-4 form-group">
                    <input type="hidden" name="types[]" value="betting_sel">
                    <label class="form-label">Betting</label>
                    <select class="form-select" name="betting_sel" required>
                        <option @if (api_setting('betting_sel') == 'clubkonnect') selected @endif value="clubkonnect">Clubkonnect
                        </option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <input type="hidden" name="types[]" value="power_sel">
                    <label class="form-label text-uppercase">Electricity API</label>
                    <select class="form-select" name="power_sel" required>
                        @foreach ($providers2 as $provider)
                            <option value="{{ $provider }}" @if (api_setting('power_sel') == $provider) selected @endif>
                                {{ ucfirst($provider) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100">Save Settings</button>
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
        function updateSystem(el, name) {
            if ($(el).is(':checked')) {
                var value = 1;
            } else {
                var value = 0;
            }
            $.post('{{ route('admin.setting.sys_settings') }}', {
                _token: '{{ csrf_token() }}',
                name: name,
                value: value
            }, function(data) {
                if (data == '1') {
                    Snackbar.show({
                        text: '{{ __('Settings Updated Successfully') }}',
                        pos: 'top-right',
                        backgroundColor: '#38c172'
                    });
                } else {
                    Snackbar.show({
                        text: '{{ __('Something went wrong') }}',
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
        .card-header {
            background-color: #fefefe;
            border-bottom: 1px solid #949d94
        }
    </style>
@endsection
