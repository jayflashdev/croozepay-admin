@extends('admin.layouts.master')
@section('title') @lang('Newsletter') @stop
@section('content')
<div class="page-title">
    <div class="d-flex align-items-center justify-content-between">
        <h5 class="mb-0">@lang('Newsletter') </h5>
    </div>
</div>
<div class="card">
    <form action="{{ route('newsletter.send') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <label class="form-label">@lang('Send Email to ')</label>
                <div class="btn-group-toggle mt-2" data-toggle="select">
                    <label class="btn btn-primary n-butn">
                        <input type="checkbox" name="user_emails" value="1" class="btn" checked>
                        <span>@lang('Users')</span>
                    </label>
                </div>                    
            </div>

            <div class="form-group">
                <label class="form-label" for="name">@lang('Other Emails') (comma separated)</label>
                <textarea class="form-control" name="other_emails" id="" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="subject">@lang('Newsletter Subject')</label>
                <input type="text" class="form-control" name="subject" id="subject" required>
            </div>
            <div class="form-group">
                <label class="form-label">@lang('Newsletter Content')</label>
                <textarea class="form-control text-editor" name="content" id="tiny1" rows="4" > </textarea>
            </div>
            <div class="form-group mb-0 text-end">
                <button class="btn btn-primary" type="submit">@lang('Send')</button>
            </div>
        </div>            
    </form>
</div>
@endsection

@section('scripts')
<!--Wysiwig js-->
<script src="{{ static_asset('tinymce/tinymce.min.js') }}"></script>
<script src="{{static_asset('tinymce/tiny-init.js') }}"></script>
@endsection