@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        All Users
                    </div>
                    <ul class="list-box list-unstyled">
                        @foreach($users as $user)
                            <li>
                                <div class="panel-body clearfix">
                                    <button class="btn btn-lg btn-primary pull-right message-button" data-toggle="modal"
                                            data-target="#new-message-{{$user->id}}">
                                        Message {{$user->name}}
                                    </button>

                                    <h4>{{$user->name}}</h4>
                                    <p class="text-muted">{{$user->email}}</p>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="new-message-{{$user->id}}" tabindex="-1" role="dialog"
                                     aria-labelledby="new-message-{{$user->id}}Label">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close"><span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="new-message-{{$user->id}}Label">
                                                    Message {{$user->name}}</h4>
                                            </div>
                                            <form action="{{route('conversation.create-conversation', $user)}}"
                                                  method="post">
                                                {!! csrf_field() !!}
                                                <div class="modal-body">
                                                    <textarea name="message" id="message" cols="30" rows="5"
                                                              placeholder="Your Message goes here..."
                                                              class="form-control"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Send Message</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </li>
                        @endforeach
                    </ul>
                    @if($users->lastPage() > 1)
                        <div class="panel-footer text-center">
                            {!! $users->render() !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
