@extends('admin.layouts.master')
@section('title') {{$title}} @stop
@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">{{$title}} </h5>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table align-items-center table-bordered table-hover" id="datatable">
            <thead>
            <tr>
                <th scope="col">Ticket</th>
                <th scope="col">Subject</th>
                <th scope="col">User</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody class="list">
            @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->ticket }} <p class="mb-0">{{show_date($ticket->updated_at)}}</p>
                    </td>
                    <td>{{ text_shortener($ticket->subject, 120) }}</td>
                    <td>{{ $ticket->user->username }}</td>
                    <td>

                        @if($ticket->status == 0)
                            <span class="badge bg-danger">Closed</span>
                        @else

                            @php
                                $reply = \App\Models\TicketComment::orderBy('id', 'DESC')->where('ticket_id', $ticket->id)->first();
                            @endphp
                            @if ($reply->type == 0)
                                <span class="badge bg-primary">@lang('customer reply')</span>
                            @else
                                <span class="badge bg-info">@lang('admin reply')</span>
                            @endif
                            <span class="badge bg-success">Open</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.support.reply', [$ticket->id, slug($ticket->ticket)]) }}" class="btn btn-rounded btn-primary"><i class="fa fa-reply"></i></a>
                        <a href="{{ route('admin.support.delete', $ticket->id) }}" class="btn btn-rounded btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-muted text-center" colspan="100%">No Support Tickets</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
@endsection