<?php

namespace App\Events;

use App\ConversationMessage;
use App\Http\Resources\MessageResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;

class NewMessage implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;
	/**
	 * @var ConversationMessage
	 */
	private $message;
	/**
	 * @var
	 */
	private $user;

	/**
	 * Create a new event instance.
	 *
	 * @param ConversationMessage $message
	 * @param                     $user
	 */
	public function __construct(ConversationMessage $message, $user)
	{
		//
		$this->message = $message;
		$this->user    = $user;
		$this->dontBroadcastToCurrentUser();
	}

	public function broadcastWith()
	{
		return [
			'message' => [
				'id'        => $this->message->id,
				'text'      => $this->message->text,
				'created'   => $this->message->created_at->toDateTimeString(),
				'file'      => $this->message->file,
				'file_type' => $this->message->file_type,
				'user'      => [
					'id'    => $this->message->participant->user->id,
					'name'  => $this->message->participant->user->name,
					'is_me' => $this->message->participant->user->id === $this->user->id,
				],
			],
		];
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		return new Channel('conversation.' . $this->message->conversation_id);
	}
}
