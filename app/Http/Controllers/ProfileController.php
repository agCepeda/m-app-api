<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class ProfileController extends Controller 
{

	public function __construct() 
	{
	}

	public function show(Request $request, $userId)
	{
		$user = User::findOrFail($userId);

		return view('profile')->with([
				'user' => $user
			]);
	}
}