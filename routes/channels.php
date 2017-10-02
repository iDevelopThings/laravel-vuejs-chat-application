<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\ConversationParticipant;

Broadcast::channel('App.User.{id}', function ($user, $id) {
	return (int)$user->id === (int)$id;
});

Broadcast::channel('conversation.{id}', function ($user, $id) {
	if (ConversationParticipant::where('conversation_id', $id)->where('user_id', $user->id)->first()) {
		return true;
	}

	return false;

	return ['true'];
});