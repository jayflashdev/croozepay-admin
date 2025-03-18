@extends('admin.layouts.master')
@section('title', 'General Settings')

@section('content')
<div class="card">
    <div class="card-header h4">Website Information </div>
    <div class="card-body">
        <form action="{{route('admin.setting.update')}}" method="post" class="row">
            @csrf
            <!-- Website Name -->
            <div class="form-group col-md-6">
                <label class="form-label">@lang('Website Name')</label>
                <input type="text" name="title" class="form-control" value="{{ get_setting('title') }}">
            </div>

            <!-- Short Name -->
            <div class="form-group col-md-6">
                <label class="form-label">@lang('Short Name')</label>
                <input type="text" name="name" class="form-control" value="{{ get_setting('name') }}">
            </div>

            <!-- Website Email -->
            <div class="form-group col-md-6">
                <label class="form-label">@lang('Website Email')</label>
                <input type="text" name="email" class="form-control" value="{{ get_setting('email') }}">
            </div>

            <!-- Support Email -->
            <div class="form-group col-md-6">
                <label class="form-label">@lang('Support Email')</label>
                <input type="text" name="support_email" class="form-control" value="{{ get_setting('support_email') }}">
            </div>

            <!-- Website Phone -->
            <div class="form-group col-md-6">
                <label class="form-label">@lang('Website Phone')</label>
                <input type="tel" name="phone" class="form-control" value="{{ get_setting('phone') }}">
            </div>

            <!-- Website About -->
            <div class="form-group col-md-6">
                <label class="form-label">@lang('Website About')</label>
                <textarea name="description" rows="3" class="form-control">{{ get_setting('description') }}</textarea>
            </div>

            <!-- API DOCS-->

            <div class="form-group col-md-6">
                <label class="form-label">@lang('Documentation Link')</label>
                <input type="text" name="docs_link" class="form-control" value="{{ get_setting('docs_link') }}">
            </div>
            <div class="form-group mb-0 text-end">
                <button class="btn-success btn w-100" type="submit">Save Settings</button>
            </div>
        </form>
    </div>
</div>
<div class="card" >
    <div class="card-header h4">Popup Notification </div>
    <div class="card-body">
        <form action="{{route('admin.setting.update')}}" method="post" class="row">
            @csrf
            <div class="form-group row">
                <label class="col-sm-3 form-label">Announcement Activation</label>
                <div class="col-sm-9 form-check form-switch">
                  <input class="ms-auto form-check-input" type="checkbox" @if (get_setting('is_announcement') == 1) checked @endif  value="1" name="is_announcement">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 form-label">{{__('Announcement')}}</label>
                <div class="col-sm-9">
                    <textarea name="announcement" rows="4" class="form-control">{{ get_setting('announcement') }}</textarea>
                </div>
            </div>
            <div class="form-group mb-0 text-end">
                <button class="btn-success btn w-100" type="submit">Save Settings</button>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header h4">Social Links </div>
    <div class="card-body">
        <form action="{{ route('admin.setting.update') }}" method="post" class="row">
            @csrf
            <!-- Facebook -->
            <div class="form-group col-md-6 col-lg-4">
                <label class="form-label">@lang('Facebook')</label>
                <input name="facebook" type="text" class="form-control" value="{{ get_setting('facebook') }}">
            </div>

            <!-- Twitter -->
            <div class="form-group col-md-6 col-lg-4">
                <label class="form-label">@lang('Twitter')</label>
                <input name="twitter" type="text" class="form-control" value="{{ get_setting('twitter') }}">
            </div>

            <!-- Telegram -->
            <div class="form-group col-md-6 col-lg-4">
                <label class="form-label">@lang('Telegram')</label>
                <input name="telegram" type="text" class="form-control" value="{{ get_setting('telegram') }}">
            </div>

            <!-- Instagram -->
            <div class="form-group col-md-6 col-lg-4">
                <label class="form-label">@lang('Instagram')</label>
                <input name="instagram" type="text" class="form-control" value="{{ get_setting('instagram') }}">
            </div>

            <!-- WhatsApp -->
            <div class="form-group col-md-6 col-lg-4">
                <label class="form-label">@lang('WhatsApp')</label>
                <input name="whatsapp" type="text" class="form-control" value="{{ get_setting('whatsapp') }}">
            </div>

            <!-- YouTube -->
            <div class="form-group col-md-6 col-lg-4">
                <label class="form-label">@lang('YouTube')</label>
                <input name="youtube" type="text" class="form-control" value="{{ get_setting('youtube') }}">
            </div>

            <!-- WhatsApp Group -->
            <div class="form-group col-md-6 col-lg-4">
                <label class="form-label">@lang('WhatsApp Group')</label>
                <input name="whatsapp_group" type="text" class="form-control" value="{{ get_setting('whatsapp_group') }}">
            </div>

            <!-- Save Button -->
            <div class="form-group col-md-12 text-end mb-0">
                <button class="btn btn-success w-100" type="submit">@lang('Save Settings')</button>
            </div>
        </form>

    </div>
</div>
<div class="card">
    <div class="card-header h4">Logo/Image Settings</div>
    <div class="card-body">
        <form class="row" action="{{route('admin.setting.update')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group col-lg-6">
                <label class="form-label">@lang('Site Logo')</label>
                <div class="col-sm-12 row">
                    <input type="file" class="form-control" name="logo" accept="image/*"/>
                    <img class="primage mt-2" src="{{ my_asset(get_setting('logo'))}}" alt="Site Logo" >
                </div>
            </div>
            <div class="form-group col-lg-6">
                <label class="form-label">@lang('Favicon')</label>
                <div class="col-sm-12">
                    <input type="file" class="form-control" name="favicon" accept="image/*"/>
                    <img class="primage mt-2" src="{{ my_asset(get_setting('favicon'))}}" alt="Favicon" >
                </div>
            </div>
            <div class="text-end">
                <button class="btn btn-success btn-block" type="submit">@lang('Update Setting')</button>
            </div>
        </form>
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
