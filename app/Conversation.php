<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
	public function messages()
	{
		return $this->hasMany(ConversationMessage::class);
	}

	public function participants()
	{
		return $this->hasMany(ConversationParticipant::class);
	}
}
