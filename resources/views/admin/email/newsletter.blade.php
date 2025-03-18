@extends('admin.layouts.master')
@section('title', 'Newsletter')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.email.newsletter') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
                <label class="form-label" for="name">@lang('Send Email to ')</label>
                <div class="btn-group-toggle mt-2" data-toggle="button">
                    <label class="btn btn-info n-butn">
                        <input type="checkbox" name="user_emails" value="1" class="btn" checked>
                        <span>@lang('Users')</span>
                    </label>
                    {{-- Select based on package --}}
                </div>                    
            </div>
            <div class="form-group">
                <label class="form-label" for="name">@lang('Other Emails') (@lang('comma separated')</label>
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
                <button class="btn btn-primary" type="submit">@lang('Send Email')</button>
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
<script src="{{static_asset('admin/libs/tinymce/tinymce.min.js')}}"></script>     
@endsection