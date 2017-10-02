<?php
/**
 * Created by PhpStorm.
 * User: Sam8t
 * Date: 02/10/2017
 * Time: 6:58 PM
 */

namespace App\Support;


use App\ConversationMessage;
use App\ConversationParticipant;
use App\User;
use Illuminate\Support\Facades\Auth;
use function public_path;
use function str_random;

class Conversation
{

	/**
	 * List conversations for a specific user or the authorised user.
	 *
	 * @param User|null $user
	 *
	 * @return mixed
	 */
	public function conversations(User $user = null)
	{
		if ($user == null) {
			$user = Auth::user();
		}

		return \App\Conversation::whereHas('participants', function ($query) use ($user) {
			return $query->where('user_id', $user->id);
		})
			->with(['messages', 'participants.user'])
			->paginate(15);
	}

	/**
	 * Checks if the user is a participant of the convesation
	 *
	 * @param \App\Conversation $conversation
	 * @param User              $user
	 *
	 * @return bool
	 */
	public function isParticipant(\App\Conversation $conversation, User $user)
	{
		return $conversation->participants()->where('user_id', $user->id)->first() != null;
	}

	/**
	 * Create a conversation between two users.
	 *
	 * @param User $user1
	 * @param User $user2
	 *
	 * @return \App\Conversation
	 */
	public function createConversation(User $user1, User $user2)
	{
		$conversation = new \App\Conversation;
		$conversation->save();

		$participant1                  = new ConversationParticipant;
		$participant1->conversation_id = $conversation->id;
		$participant1->user_id         = $user1->id;
		$participant1->save();

		$participant2                  = new ConversationParticipant;
		$participant2->conversation_id = $conversation->id;
		$participant2->user_id         = $user2->id;
		$participant2->save();

		return $conversation;
	}

	/**
	 * Create a message in the conversation
	 * Text and File are taken from the request
	 *
	 * @param \App\Conversation $conversation
	 *
	 * @param                   $text
	 * @param                   $file
	 *
	 * @return ConversationMessage
	 * @internal param $message
	 */
	public function sendMessage(\App\Conversation $conversation, $text, $file = null)
	{
		$user        = Auth::user();
		$participant = $conversation->participants()->where('user_id', $user->id)->first();

		$message                              = new ConversationMessage;
		$message->conversation_id             = $conversation->id;
		$message->conversation_participant_id = $participant->id;
		$message->text                        = $text;
		if ($file != null) {
			$file     = request()->file('file');
			$fileName = str_random() . '.' . $file->getClientOriginalExtension();

			$file->move(public_path() . '/uploads', $fileName);
			$message->file      = '/uploads/' . $fileName;
			$message->file_mime = mime_content_type(public_path() . '/uploads/' . $fileName);//Had to do it this way due to a local server problem...
			$message->file_type = $this->fileType($message->file_mime);
		}
		$message->save();

		return $message;
	}

	public function fileType($mimeType)
	{
		switch ($mimeType) {
			case strstr($mimeType, "video/"):
				return "video";
				break;
			case strstr($mimeType, "image/"):
				return "image";
				break;
			case strstr($mimeType, "audio/"):
				return "audio";
				break;
			case strstr($mimeType, "text/"):
				return "text";
				break;
			case strstr($mimeType, "application/x-7z-compressed"):
				return "compressed";
				break;
			case strstr($mimeType, "application/x-rar-compressed"):
				return "compressed";
				break;
			case strstr($mimeType, "application/x-gtar"):
				return "compressed";
				break;
			case strstr($mimeType, "application/zip"):
				return "compressed";
				break;
		}

		return null;
	}

}