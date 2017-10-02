<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Http\Resources\MessageResource;
use App\Support\Conversation;
use App\User;
use function broadcast;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use function trim;

class ConversationController extends Controller
{
	/**
	 * @var Conversation
	 */
	private $conversation;

	public function __construct(Conversation $conversation)
	{
		$this->conversation = $conversation;
	}

	public function conversations()
	{
		return view('conversations.view-all', [
			'conversations' => $this->conversation->conversations(),
		]);
	}

	public function conversation(\App\Conversation $conversation)
	{
		$conversation->load('participants.user');

		return view('conversations.conversation', [
			'conversation' => $conversation,
		]);
	}

	public function createConversation(User $user)
	{
		$conversation = $this->conversation->createConversation(Auth::user(), $user);
		$this->conversation->sendMessage($conversation, request('message'));

		return redirect()->route('conversation.view', $conversation);
	}

	public function messages(\App\Conversation $conversation)
	{
		$messages = $conversation->messages()->with('participant.user')->latest()->paginate(10);

		return MessageResource::collection($messages);
	}

	public function sendMessage(\App\Conversation $conversation)
	{
		if (trim(request('message')) === "" && !request()->hasFile('file')) {
			return response()->json(['message' => 'You must enter a message to send.'], 400);
		}

		$message = $this->conversation->sendMessage($conversation, request('message'), request('file'));
		$message->load('participant.user');

		broadcast(new NewMessage($message, Auth::user()));

		return new MessageResource($message);
	}
}
