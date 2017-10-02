<?php

namespace Tests\Feature;

use App\ConversationMessage;
use App\Support\Conversation;
use App\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConversationTest extends TestCase
{
	use RefreshDatabase;

	private $conversation;

	public function setUp()
	{
		$this->conversation = new Conversation();
		parent::setUp();
	}

	public function testConversationCreated()
	{
		$user1 = factory(User::class)->create();
		$user2 = factory(User::class)->create();

		$conversation = $this->conversation->createConversation($user1, $user2);

		$this->assertInstanceOf(\App\Conversation::class, $conversation);

	}

	public function testUserIsParticipantOfConversation()
	{
		$user1 = factory(User::class)->create();
		$user2 = factory(User::class)->create();

		$conversation = $this->conversation->createConversation($user1, $user2);

		$user1Check = $this->conversation->isParticipant($conversation, $user1);
		$this->assertTrue($user1Check);

		$user2Check = $this->conversation->isParticipant($conversation, $user2);
		$this->assertTrue($user2Check);
	}

	public function testMessageWasSentInConversation()
	{
		$user1 = factory(User::class)->create();
		$user2 = factory(User::class)->create();

		$conversation = $this->conversation->createConversation($user1, $user2);

		$this->actingAs($user1);
		$message = $this->conversation->sendMessage($conversation, "Hello!");

		$this->assertInstanceOf(ConversationMessage::class, $message);
		$this->assertEquals('Hello!', $message->text);
	}

}
