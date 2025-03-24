@extends('admin.layouts.master')
@section('title', 'API Websites')

@section('content')
<div class="row">
    {{-- Adex Websites --}}
    <h5 class="col-12 card-header text-center mb-2">ADEX Websites</h5>
    @for ($i = 1; $i <= 6; $i++)
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header h6">Adex {{ $i }} API</div>
                <div class="card-body">
                    <form action="{{ route('admin.setting.api_settings') }}" method="post" class="ajaxForm">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="adex{{ $i }}_website">
                            <label class="form-label">Website</label>
                            <input type="text" class="form-control" name="adex{{ $i }}_website" value="{{ api_setting('adex' . $i . '_website') }}" placeholder="https://n3tdata.com/api" />
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="adex{{ $i }}_username">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="adex{{ $i }}_username" value="{{ api_setting('adex' . $i . '_username') }}" />
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="adex{{ $i }}_password">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="adex{{ $i }}_password" value="{{ api_setting('adex'. $i .'_password') }}" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-3">Save</button>
                    </form>
                </div>
            </div>
        </div>
    @endfor
    {{-- Msorg Websites --}}
    <h5 class="col-12 card-header text-center mb-2">MSORG Websites</h5>
    @for ($i = 1; $i <= 6; $i++)
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header h6">Msorg {{ $i }} API</div>
                <div class="card-body">
                    <form action="{{ route('admin.setting.api_settings') }}" method="post" class="ajaxForm">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="msorg{{ $i }}_website">
                            <label class="form-label">Website</label>
                            <input type="text" class="form-control" name="msorg{{ $i }}_website" value="{{ api_setting('msorg' . $i . '_website') }}" placeholder="https://gladtidingsdata.com" />
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="msorg{{ $i }}_token">
                            <label class="form-label">API Token </label>
                            <input type="text" class="form-control" name="msorg{{ $i }}_token" value="{{ api_setting('msorg' . $i . '_token') }}" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-3">Save</button>
                    </form>
                </div>
            </div>
        </div>
    @endfor
    <div class="col-sm-6 col-lg-4">
        <div class="card">
            <h6 class="card-header">VTPass</h6>
            <div class="card-body">
                <form action="{{ route('admin.setting.api_settings') }}" method="post" class="ajaxForm">
                    @csrf

                <div class="form-group">
                        <input type="hidden" name="types[]" value="vt_username">
                        <label class="form-label">VTpass Email</label>
                        <input type="text" class="form-control" name="vt_username" value="{{api_setting('vt_username')}}" />
                    </div>
                    <div class="form-group ">
                        <input type="hidden" name="types[]" value="vt_password">
                        <label class="form-label">VTpass Password</label>
                        <input type="password" class="form-control" name="vt_password" value="{{api_setting('vt_password')}}" />
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary w-100" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card">
            <h6 class="card-header">Clubkonnect</h6>
            <div class="card-body">
                <form action="{{ route('admin.setting.api_settings') }}" method="post" class="ajaxForm">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="clubkonnect_key">
                        <label class="form-label">API Key</label>
                        <input type="text" class="form-control" name="clubkonnect_key" value="{{api_setting('clubkonnect_key')}}" />
                    </div>

                <div class="form-group">
                        <input type="hidden" name="types[]" value="clubkonnect_id">
                        <label class="form-label">User ID</label>
                        <input type="text" class="form-control" name="clubkonnect_id" value="{{api_setting('clubkonnect_id')}}" />
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary w-100" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card">
            <h6 class="card-header">Ncwallet</h6>
            <div class="card-body">
                <form action="{{ route('admin.setting.api_settings') }}" method="post" class="ajaxForm">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="ncwallet_apikey">
                        <label class="form-label">API Key</label>
                        <input type="text" class="form-control" name="ncwallet_apikey" value="{{api_setting('ncwallet_apikey')}}" />
                    </div>

                <div class="form-group">
                        <input type="hidden" name="types[]" value="ncwallet_pin">
                        <label class="form-label">Trxn PIN</label>
                        <input type="password" class="form-control" name="ncwallet_pin" value="{{api_setting('ncwallet_pin')}}" />
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary w-100" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card">
            <h6 class="card-header">Easyaccess</h6>
            <div class="card-body">
                <form action="{{ route('admin.setting.api_settings') }}" method="post" class="ajaxForm">
                    @csrf
                    <div class="form-group">
                         <input type="hidden" name="types[]" value="easyaccess_key">
                         <label class="form-label">Easyaccess Key</label>
                         <input type="text" class="form-control" name="easyaccess_key" value="{{api_setting('easyaccess_key')}}" />
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary w-100" type="submit">Save</button>
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

@section('scripts')
<script>

</script>
@endsection
@section('styles')
<style>
    .card-header{background-color: #fefefe; border-bottom:1px solid #949d94 }
</style>
@endsection
