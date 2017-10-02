<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

class MessageResource extends Resource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'id'        => $this->id,
			'text'      => $this->text,
			'created'   => $this->created_at->toDateTimeString(),
			'file'      => $this->file,
			'file_type' => $this->file_type,
			'user'      => [
				'id'    => $this->participant->user->id,
				'name'  => $this->participant->user->name,
				'is_me' => $this->participant->user->id === Auth::id(),
			],
		];
	}
}
