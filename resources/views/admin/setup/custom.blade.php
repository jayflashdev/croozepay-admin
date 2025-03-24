@extends('admin.layouts.master')
@section('title', 'Custom CSS & JS')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{route('admin.setting.update')}}"  method="post">
            @csrf
            <div class="form-group">
                <label for="" class="form-label">Custom CSS</label>
                <textarea class="form-control" name="custom_css" placeholder="<style> ... </style>" rows="10">{{get_setting('custom_css')}}</textarea>
            </div>
            <div class="form-group mt-2">
                <label for="" class="form-label">Custom Javascripts</label>
                <textarea class="form-control" name="custom_js" placeholder="<script> ... </script>" rows="10">{{get_setting('custom_js')}}</textarea>
            </div>
            <div class="form-group text-end">
                <button type="submit" class="btn btn-primary">Save</button>
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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                    <li class="breadcrumb-item active">@yield('title')</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
@endsection