@extends('admin.layouts.master')
@section('title') {{$title}} @stop
@section('content')

<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">{{$title}} </h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mb-5">
                <div class="chatArea">                    
                    @forelse($ticket->comments as $comment)
                        @if($comment->type == 0)
                        <div class="row justify-content-start">
                            <div class="col-sm-10 card msg bg-primary">
                                <div class="row">  
                                    <div class="col-md-2">
                                        <img src="{{ static_asset('user.png') }}" alt="">
                                    </div>                                              
                                    <div class="col-md-10">
                                        <p>{{ $comment->comment }}</p>
                                    </div>
                                    <p class="small text-start mb-0">{{show_datetime($comment->created_at)}} </p>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row justify-content-end">
                            <div class="col-sm-10 card msg">
                                <div class="row">
                                    <div class="col-md-10">
                                        <p>{{ $comment->comment }}</p>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <img src="{{ my_asset(get_setting('favicon')) }}" alt="">
                                    </div>
                                    <p class="small text-end mb-0">{{show_datetime($comment->created_at)}} </p>
                                </div>
                            </div>
                        </div>

                        @endif
                    @empty
                        <div class="row">
                            <div class="col-md-12 card msg">
                                <h5>No ticket message</h5>

                            </div>
                        </div>
                    @endforelse
                    
                </div>
            </div>
            <div class="col-md-12">
                <form action="{{ route('admin.support.comment', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="comment" class="form-control commentBox" rows="4" placeholder="@lang('Your message')">{{ old('comment') }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
@section('styles')
<style>
    textarea.commentBox,textarea.commentBox:focus {
        overflow-y: auto;
    }

    .chatArea {
        padding: 20px;
        background: transparent;
        border: 1px solid #f7f7f7;
    }

    .chatArea .msg {
        background: #0f327e;
        padding: 20px;
        margin-bottom: 10px;
    }

    .chatArea .msg p{
        color: #fff;
        font-size: 0.9rem;
        margin-bottom: 0;
    }


    .msg .row {
        margin-right: -5px;
        margin-left: -5px;
    }

    .msg .row [class*="col"] {
        padding-right: 5px;
        padding-left: 5px;
    }

    .msg img {
        height: 50px;
        width: 50px;
        border-radius: 50%;
        background-size: cover;
    }
</style>
@endsection