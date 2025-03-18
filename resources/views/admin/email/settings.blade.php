@extends('admin.layouts.master')
@section('title', 'Email Settings')

@section('content')
<div class="row"> 
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.setting.env_key') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label">{{__('MAIL MAILER')}}</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" value="{{  env('MAIL_MAILER') }}" disabled> 
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="MAIL_HOST">
                        <div class="col-lg-4">
                            <label class="form-label">{{__('MAIL HOST')}}</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="MAIL_HOST" value="{{  env('MAIL_HOST') }}" placeholder="{{__('MAIL HOST')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="MAIL_PORT">
                        <div class="col-lg-4">
                            <label class="form-label">{{__('MAIL PORT')}}</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="MAIL_PORT" value="{{  env('MAIL_PORT') }}" placeholder="{{__('MAIL PORT')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="MAIL_USERNAME">
                        <div class="col-lg-4">
                            <label class="form-label">{{__('MAIL USERNAME')}}</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="MAIL_USERNAME" value="{{  env('MAIL_USERNAME') }}" placeholder="{{__('MAIL USERNAME')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="MAIL_PASSWORD">
                        <div class="col-lg-4    ">
                            <label class="form-label">{{__('MAIL PASSWORD')}}</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="password" class="form-control" name="MAIL_PASSWORD" value="{{  env('MAIL_PASSWORD') }}" placeholder="{{__('MAIL PASSWORD')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="MAIL_ENCRYPTION">
                        <div class="col-lg-4">
                            <label class="form-label">{{__('MAIL ENCRYPTION')}}</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="MAIL_ENCRYPTION" value="{{  env('MAIL_ENCRYPTION') }}" placeholder="{{__('MAIL ENCRYPTION')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="MAIL_FROM_ADDRESS">
                        <div class="col-lg-4">
                            <label class="form-label">{{__('MAIL FROM ADDRESS')}}</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="MAIL_FROM_ADDRESS" value="{{  env('MAIL_FROM_ADDRESS') }}" placeholder="{{__('MAIL FROM ADDRESS')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="MAIL_FROM_NAME">
                        <div class="col-lg-4">
                            <label class="form-label">{{__('MAIL FROM NAME')}}</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="MAIL_FROM_NAME" value="{{  env('MAIL_FROM_NAME') }}" placeholder="{{__('MAIL FROM NAME')}}">
                        </div>
                    </div>      
                    <div class="form-group mb-0 text-end">
                        <button class="btn btn-primary" type="submit">{{__('Save')}}</button>
                    </div> 
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{__('Test SMTP configuration')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.email.test') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <input type="email" class="form-control" name="email"  placeholder="{{ __('Enter your email address') }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">{{ __('Send test email') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>{{__('Instruction')}}</h4>
            </div>
            <div class="card-body">
                <b>{{ __('For Non-SSL') }}</b>
                <ul class="list-group">
                    <li class="list-group-item">{{__('Set Mail Host according to your server Mail Client Manual Settings')}}</li>
                    <li class="list-group-item">{{__("Set Mail port as 587")}}</li>
                    <li class="list-group-item">{{__("Set Mail Encryption as 'ssl' if you face issue with tls")}}</li>
                </ul>
                <br>
                <b>{{ __('For SSL') }}</b>
                <ul class="list-group">
                    <li class="list-group-item">{{__('Set Mail Host according to your server Mail Client Manual Settings')}}</li>
                    <li class="list-group-item">{{__('Set Mail port as 465')}}</li>
                    <li class="list-group-item">{{__('Set Mail Encryption as ssl')}}</li>
                </ul>
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