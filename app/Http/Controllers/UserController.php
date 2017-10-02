<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function users()
	{
		return view('users', [
			'users' => User::paginate(10),
		]);
	}
}
