<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;

use App\Notifications\Publisher;
use App\Http\Controllers\Controller;

use App\User;
use App\Contact;
use App\Constants;

class ContactController extends Controller {

	public function __construct()
	{
		$this->middleware('auth.user');
	}

	public function index($userId, Request $request) 
	{
		$user = User::findOrFail($userId); 

        // $contacts2 = Contact::where('contact1', $user->id)
        //     ->select(['contact2'])
        //     ->map(function($item) {
        //         return $item->contact2;
        //     });

        return $user
        		->following()
                ->orderBy('show_name', 'ASC')
                ->with('card')
                ->get([
                	app('db')->raw('users.*'),
                	app('db')->raw("concat(users.name, ' ', users.last_name) as show_name")
                ]);
	}

	public function getContacts(User $user, Request $request)
	{
        return $user
        		->following()
                ->orderBy('show_name', 'ASC')
                ->with('card')
                ->get([
                	app('db')->raw('users.*'),
                	app('db')->raw("concat(users.name, ' ', users.last_name) as show_name")
                ]);
	}

	public function addContact(
		User $user,
		Request $request,
		Publisher $publisher
	) {
		app('db')->beginTransaction();
		$contactId = $request->get('contact_id');

		try {
			app('db')
				->table('contacts')
				->insert([
					'contact1' => $user->id,
					'contact2' => $contactId 
				]);

			app('db')
				->table('notifications')
				->where('user_id', $contactId)
				->where('notification_type_id', Constants::NOTIFICATION_TYPE_FOLLOWER)
				->where('attachment', $user->id)
				->update([
					'seen' => true
				]);


			app('db')
				->table('notifications')
				->insert([
					'user_id' => $contactId,
					'notification_type_id' => Constants::NOTIFICATION_TYPE_FOLLOWER,
					'attachment' => $user->id
				]);

			$publisher->sendFollowerNotificationToUser($contactId);

			app('db')->commit();
		} catch (\Exception $ex) {
			app('db')->rollBack();
			app('log')->error($ex);
			return response()->json(['message' => ''], 400);
		}
	}

	public function removeContact($contactId, User $user, Request $request)
	{
		app('db')->table('contacts')
			->where('contact1', $user->id)
			->where('contact2', $contactId)
			->delete();
/*
		app('db')->table('contacts')
			->where('contact1', $contactId)
			->where('contact2', $user->id)
			->delete();*/
	}
	
}