@extends('admin.layouts.master')
@section('title', 'Create Page')

@section('content')
<div class="card">
    <form class="card" action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
		@csrf
		<div class="card-header">
			<h6 class="fw-bold mb-0">@lang('Create Page') </h6>
		</div>
		<div class="card-body">
			<div class="form-group row">
				<label class="col-sm-2 form-label" for="name">@lang('Title') <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="@lang('Title')" name="title" required>
				</div>
			</div>

            <div class="form-group row">
                <label class="col-sm-2 form-label" for="name">@lang('Link') <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <div class="input-group d-md-flex d-sm-block">
                        <span class="input-group-text">{{ route('index') }}/page/</span>
                        <input type="text" class="form-control w-sm-100" placeholder="@lang('Slug')" name="slug" required>
                    </div>                    
                    <small class="form-text text-danger">@lang('Only a-z, numbers, hypen allowed') </small>
                </div>
            </div>

			<div class="form-group row">
				<label class="col-sm-2 form-label" for="name">@lang('Content') <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<textarea class="form-control" placeholder="@lang('Content')" id="tiny2" name="content" rows="4"> </textarea>
				</div>
			</div>

            <div class="me-2 text-end">
				<button type="submit" class="btn btn-primary">@lang('Create Page')</button>
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