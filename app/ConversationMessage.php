<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConversationMessage extends Model
{
	public function participant()
	{
		return $this->belongsTo(ConversationParticipant::class, 'conversation_participant_id');
	}

	public function conversation()
	{
		return $this->belongsTo(Conversation::class);
	}
}
