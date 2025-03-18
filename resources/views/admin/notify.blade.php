@extends('admin.layouts.master')
@section('title', 'Notifications')

@section('content')
<div class="container">
    <!-- Create Notification Form -->
    <div class="card mb-4">
        <div class="card-header">Create New Notification</div>
        <div class="card-body">
            <form action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Create Notification</button>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card">
        <div class="card-header">Notifications</div>
        <div class="card-body">
            @if($notifications->isEmpty())
                <p>No notifications available.</p>
            @else
                <ul class="list-group">
                    @foreach($notifications as $notification)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>{{ $notification->title }}</strong>
                            <p>{{ $notification->message }}</p>

                            <!-- Delete Button -->
                            <a href="{{ route('admin.notifications.delete', $notification->id) }}" type="submit" class="btn btn-danger btn-sm delete-btn" >
                                    Delete
                            </a>
                        </li>
                    @endforeach
                </ul>
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
