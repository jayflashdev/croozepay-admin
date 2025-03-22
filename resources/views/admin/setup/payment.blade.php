@extends('admin.layouts.master')
@section('title', 'Payment Settings')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="my-2">
                <p class="fw-bold mb-0">Monnify Webhook</p>
                {{-- <span class="form-control">{{ route('monnify.webhook') }}</span> --}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-6">
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
                                <input type="checkbox" onchange="updateSystem(this, 'monnify_payment')"
                                    @if (sys_setting('monnify_payment') == 1) checked @endif>
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
                                <input type="checkbox" onchange="updateSystem(this, 'monnify_demo')"
                                    @if (sys_setting('monnify_demo') == 1) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-6">
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
                            <input type="text" class="form-control" name="MONNIFY_API_KEY"
                                value="{{ env('MONNIFY_API_KEY') }}" placeholder="Monnify Api key" required>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="MONNIFY_CONTRACT">
                            <label class="form-label">Monnify Contract</label>
                            <input type="text" class="form-control" name="MONNIFY_CONTRACT"
                                value="{{ env('MONNIFY_CONTRACT') }}" placeholder="Monnify Contract Code" required>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="MONNIFY_SECRET_KEY">
                            <label class="form-label">Monnify Secret</label>
                            <input type="text" class="form-control" name="MONNIFY_SECRET_KEY"
                                value="{{ env('MONNIFY_SECRET_KEY') }}" placeholder="Monnify secret key" required>
                        </div>
                        <div class="form-group mb-0 text-end">
                            <button type="submit" class="btn btn-sm w-100 btn-primary">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="card">
        <div class="card-header h4">Currency Settings</div>
        <div class="card-body">
            <form class="row" action="{{ route('admin.setting.update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group col-sm-6 ">
                    <label class="form-label">Currency Symbol</label>
                    <input type="text" class="form-control" name="currency" value="{{ get_setting('currency') }}"
                        required placeholder="Currency Symbol" />
                </div>
                <div class="form-group col-sm-6">
                    <label class="form-label">Currency Code</label>
                    <input type="text" class="form-control" name="currency_code"
                        value="{{ get_setting('currency_code') }}" required placeholder="Currency Code" />
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
        .primage {
            max-height: 50px !important;
            max-width: 150px !important;
            margin: 0;
        }

        .card-header {
            border-top: 1px solid #1d1f1d
        }
    </style>
@endsection
