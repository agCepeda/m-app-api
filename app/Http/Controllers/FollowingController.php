<?php

namespace App\Http\Controllers;

use App\User;

class FollowingController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.user');
	}

	public function index($userId = null)
	{
		if ($userId != null) {
			return User::find($userId)
						->following()
						->get();
		} else {
			$user = app('App\User');
			return $user->following()
						->get();
		}
	}
}