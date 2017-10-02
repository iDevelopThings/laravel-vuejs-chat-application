<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConversationParticipant extends Model
{
	public function conversation()
	{
		return $this->belongsTo(Conversation::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function messages()
	{
		return $this->hasMany(ConversationMessage::class);
	}

}
