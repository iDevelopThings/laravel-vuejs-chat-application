<?php

use App\Conversation;
use App\ConversationMessage;
use App\ConversationParticipant;
use App\User;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$user1 = User::first();
		$user2 = factory(User::class)->create();

		//Create Conversation
		$convo = new Conversation;
		$convo->save();

		//Add registered user
		$participant1                  = new ConversationParticipant;
		$participant1->user_id         = $user1->id;
		$participant1->conversation_id = $convo->id;
		$participant1->save();

		//Add created user
		$participant2                  = new ConversationParticipant;
		$participant2->user_id         = $user2->id;
		$participant2->conversation_id = $convo->id;
		$participant2->save();

		//Send message from user 1 to user 2
		$message                              = new ConversationMessage;
		$message->conversation_id             = $convo->id;
		$message->conversation_participant_id = $participant1->id;
		$message->text                        = "Hello, " . $user2->name;
		$message->save();

		//Send message from user 2 to user 1
		$message                              = new ConversationMessage;
		$message->conversation_id             = $convo->id;
		$message->conversation_participant_id = $participant2->id;
		$message->text                        = "Hello, " . $user1->name;
		$message->save();

	}
}
