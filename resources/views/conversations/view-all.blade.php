@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Conversations
                    </div>
                    @if(!$conversations->first())
                        <div class="panel-body text-center">
                            <h3>
                                You have not started any conversations.
                            </h3>
                        </div>
                    @else
                        <ul class="list-box list-unstyled">
                            @foreach($conversations as $conversation)
                                @php $otherUser = $conversation->participants->where('user_id', '!=', Auth::id())->first(); @endphp
                                @php $lastMessage = $conversation->messages->last(); @endphp

                                <li>
                                    <div class="panel-body clearfix">
                                        <a href="{{route('conversation.view', $conversation)}}"
                                           class="btn btn-default pull-right conversation-button">
                                            View Conversation
                                        </a>

                                        <h4>Conversation with {{$otherUser->user->name}}</h4>

                                        @if($lastMessage)
                                            <p class="text-muted">{{$lastMessage->text}}</p>
                                            <small>Last message sent
                                                <strong>{{$lastMessage->created_at->diffForHumans()}}</strong></small>
                                        @else
                                            <p class="text-muted">Nobody has sent a message in this conversation
                                                yet.</p>
                                        @endif

                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @if($conversations->lastPage() > 1)
                            <div class="panel-footer text-center">
                                {!! $conversations->render() !!}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
