@extends('admin.layouts.master')
@section('title', 'Edit Page')

@section('content')
<div class="card">
    <form class="card" action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
		@csrf
		<div class="card-header">
			<h6 class="fw-bold mb-0">@lang('Update Page') </h6>
		</div>
		<div class="card-body">
			<div class="form-group row">
				<label class="col-sm-2 form-label" for="name">@lang('Title') <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="@lang('Title')" value="{{$page->title}}" name="title" required>
				</div>
			</div>
            <div class="form-group row">
                <label class="col-sm-2 form-label" for="name">{{__('Link')}} <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <div class="input-group d-md-flex d-sm-block">
                        @if($page->type == 'custom')
                            <span class="input-group-text">{{ route('index') }}/page/</span>
                            <input type="text" class="form-control w-sm-100" placeholder="{{ __('Slug') }}" name="slug" value="{{ $page->slug }}">
                        @else
                            <input class="form-control w-100 w-md-auto" value="{{ route('index') }}/{{ $page->slug }}" disabled>
                        @endif
                    </div>
                    
                    <small class="form-text text-danger">{{ __('Only a-z, numbers, hypen allowed') }}</small>
                </div>
            </div>

			<div class="form-group row">
				<label class="col-sm-2 form-label" for="name">@lang('Content') <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<textarea class="form-control" placeholder="@lang('Content')" id="tiny2" name="content"rows="4">{{$page->content}}</textarea>
				</div>
			</div>

            <div class="me-2 text-end">
				<button type="submit" class="btn btn-primary">@lang('Update Page')</button>
			</div>
		</div>
	</form>
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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Page</a></li>
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