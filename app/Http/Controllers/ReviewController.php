<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\User;
use App\Review;

use Illuminate\Http\Request;

use App\Notifications\Publisher;

class ReviewController extends Controller 
{

	public function __construct() 
	{
		$this->middleware('auth.user');
	}


	public function index($userId, Request $request) 
	{
		$paginate = 10;

		return Review::query()
				->with([
					'user' => function($query) {
						return $query->select(['id', 'name', 'last_name']);
					}, 
					'reviewer' => function($query) {
						return $query->select(['id', 'name', 'last_name']);
					}
				])
				->where('user_id', $userId)
				->paginate($paginate);
	}

	public function create($userId, User $user, Request $request, Publisher $publisher) 
	{
		$db = app('db');
		try {
			$reviewId = $db->table('reviews')
				->insertGetId([
					'user_id'     => $userId,
					'reviewer_id' => $user->id,
					'comment'     => $request->get('comment'),
					'score'       => $request->get('score')
				]);

			$review = $db->table('reviews')
				->where('reviews.id', $reviewId)
				->join('users', 'reviews.reviewer_id', '=', 'users.id')	
				->select([
					'reviews.id',
					'reviews.comment',
					'reviews.score',
					$db->raw('CONCAT(users.name, " ", users.last_name) AS reviewer_name')
					])
				->first();

			$publisher->sendReviewNotificationToUser($userId, $user->id);

			return response()->json($review);
		} catch (\Exception $ex) {
			return response()->json(['message' => ''], 500);
		}
	}

}