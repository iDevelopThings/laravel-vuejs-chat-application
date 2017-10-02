<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversationMessagesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('conversation_messages', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('conversation_id')->unsigned()->index();
			$table->integer('conversation_participant_id')->unsigned()->index();
			$table->longText('text')->nullable();
			$table->string('file')->nullable();
			$table->string('file_mime')->nullable();
			$table->string('file_type')->nullable();
			$table->timestamps();
			$table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
			$table->foreign('conversation_participant_id')->references('id')->on('conversation_participants')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('conversation_messages');
	}
}
