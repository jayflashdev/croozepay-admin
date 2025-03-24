@extends('admin.layouts.master')
@section('title', 'Notifications')

@section('content')
    <div class="container">
        <!-- Create Notification Card -->
        <div class="card card-hover">
            <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white ">Create New Notification</h5>
                <i class="fas fa-bell fs-5"></i>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.notifications.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="form-label fw-semibold">Notification Title</label>
                        <input type="text" id="title" name="title" class="form-control form-control-lg"
                            placeholder="Enter notification title" required>
                    </div>

                    <div class="mb-4">
                        <label for="message" class="form-label fw-semibold">Message Content</label>
                        <textarea id="message" name="message" class="form-control" rows="5" placeholder="Write your message here..."
                            required></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Send Notification
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Notifications List -->
        <div class="card card-hover mt-4">
            <div class="card-header bg-info d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white">Recent Notifications</h5>
                <span class="badge bg-light text-dark">{{ $notifications->count() }} Total</span>
            </div>
            <div class="card-body p-0">
                @if ($notifications->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted"></i>
                        <p class="mt-3 text-muted">No notifications found</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach ($notifications as $notification)
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="me-3">
                                        <h6 class="mb-1 fw-semibold text-primary">{{ $notification->title }}</h6>
                                        <p class="mb-0 text-muted">{{ $notification->message }}</p>
                                        <small class="text-muted">
                                            Created: {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        <a href="{{ route('admin.notifications.delete', $notification->id) }}"
                                            class="btn btn text-danger delete-btn" data-bs-toggle="tooltip"
                                            title="Delete Notification">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
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
