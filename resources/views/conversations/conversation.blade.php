@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @php
                    $otherParticipant = $conversation->participants->where('user_id', '!=', Auth::id())->first();
                @endphp
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Conversation
                        with {{$otherParticipant->user->name}}
                    </div>

                    <chat conversation-id="{{$conversation->id}}" participant-id="{{$otherParticipant->id}}"
                          participant-name="{{$otherParticipant->user->name}}"></chat>
                </div>
            </div>
        </div>
    </div>
@endsection
