@extends('admin.layouts.master')
@section('title', 'Edit Template')

@section('content')
<div class="row">
    <div class="card col-lg-4">
        <div class="card-body table-responsive">
            <table class="table-hover table table-bordered" >
                <thead>
                    <tr>
                        <th>Short Code</th>
                        <th>Description</th>
                    </tr>
                </thead>
                
                <tbody class="list">
                    @forelse($template->shortcodes as $shortcode => $key)
                        <tr>
                            <td>@php echo "{{". $shortcode ."}}"  @endphp</td>
                            <td>{{ $key }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-muted text-center">@lang('No shortcode available')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card col-lg-8">
        <div class="card-body">
            <form action="{{ route('admin.email.update_template', $template->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="font-weight-bold">Subject <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg" placeholder="@lang('Email subject')" name="subject" value="{{ $template->subject }}"/>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">@lang('Message') <span class="text-danger">*</span></label>
                    <textarea name="content" rows="10" class="form-control" id="tiny2" placeholder="@lang('Your message using shortcodes')">{{ $template->content }}</textarea>
                </div>
                <div class="form-group text-end">
                    <button type="submit" class="btn btn-primary me-2">@lang('Submit')</button>
                </div>
            </form>
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
<script src="{{static_asset('admin/libs/tinymce/tinymce.min.js')}}"></script>     
@endsection