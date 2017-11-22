<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\Repositories\UserRepository;

use App\User;
use App\Profession;

use Log;

class UserController extends Controller
{
    const MSG_USER_NOT_FOUND = 'No se encontro usuario';

    /**
    * Crea una nueva instancia del controllador
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth.user');
    }


    public function index(
        Request $request,
        UserRepository $userRepo
    ) {
        $q    = $request->get('q');
        $page = $request->get('page', 1);
        $size = $request->get('size', 30);


        return $userRepo->search($q, $size, $page);
    }

    public function update(
        Request $request,
        UserRepository $userRepo
    ) {
        $profileData = $request->all();

        if (array_key_exists('card', $profileData)) {
            $profileData['card_id'] = $profileData['card'];
        }

        $logoFile = $request->file('logo');
        $profilePicture = $request->file('profile_picture');

        return response()
                    ->json(
                        $userRepo->updateProfile($profileData, $logoFile, $profilePicture)
                    );
    }

	public function show($userId, User $user, Request $request)
	{
		$with = $request->get('with');
		$with = $with != null ? explode(",", $with) : [];

		$db = app('db');

		$contact = User::query()
			->with('profession', 'card')
			->withCount(['followers', 'following'])
			->where('id', $userId)
			->first();


		if (isset($contact) && $contact != null) {
			$contact = $contact->toArray();

			$numContact = $db->table('contacts')
				->where('contact1', $user->id)
				->where('contact2', $userId)
				->count();

			$contact['contact'] = $numContact > 0;

			if(in_array('my_review', $with)) {
				$review = $db->table('reviews')
					->join('users', 'reviews.reviewer_id', '=', 'users.id')
					->where('user_id', $userId)
					->where('reviewer_id', $user->id)
					->select([
						'users.id',
						'reviews.comment',
						$db->raw('CONCAT(users.name, " ", users.last_name) AS reviewer_name'),
						'reviews.score'
						])
					->first();

				if ($review && isset($review) && $review != null) {
					$contact['review'] = $review;
				}
			}
			return response()->json($contact);
		}

		return response()->json(['message' => self::MSG_USER_NOT_FOUND], 404);
	}

	public function showMyProfile() {
		return 13;
	}

	private function createRandomString($extra) {
		$time    = time();
		$md5Time = md5($time);
		$str     = $time . $md5Time . $extra;

		//$randString = md5($str);

		return $str;
	}
}
